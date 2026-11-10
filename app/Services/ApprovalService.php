<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\StatusPengajuan;
use App\Events\PengajuanApproved;
use App\Events\PengajuanNomorGenerated;
use App\Events\PengajuanRejected;
use App\Events\PengajuanStatusChanged;
use App\Models\ApprovalLog;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    public function __construct(
        private readonly NomorSuratService $nomorService,
    ) {}

    public function verifikasiAdmin(PengajuanSurat $pengajuan, User $admin, ?string $catatan = null): void
    {
        $this->pastikanStep($pengajuan, 1);

        $fresh = DB::transaction(function () use ($pengajuan, $admin, $catatan) {
            $pengajuan->update([
                'status' => StatusPengajuan::DIPROSES,
                'current_step' => 2,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'catatan_reviewer' => $catatan,
            ]);

            $this->catatLog($pengajuan, $admin, 1, 'approve', $catatan);

            return $pengajuan->refresh();
        });

        $this->broadcastKeRole($fresh, ['pimpinan', 'guru'], 'verified');
        event(new PengajuanApproved($fresh, 1));
    }

    public function approvePimpinan(PengajuanSurat $pengajuan, User $pimpinan, ?string $catatan = null): PengajuanSurat
    {
        $this->pastikanStep($pengajuan, 2);

        $fresh = DB::transaction(function () use ($pengajuan, $pimpinan, $catatan) {
            $nomorSurat = $this->nomorService->generateNomorSurat($pengajuan->blanko->kode);

            $pengajuan->update([
                'nomor_surat' => $nomorSurat,
                'status' => StatusPengajuan::DISETUJUI,
                'current_step' => 3,
                'reviewed_by' => $pimpinan->id,
                'reviewed_at' => now(),
                'catatan_reviewer' => $catatan,

            ]);

            $this->catatLog($pengajuan, $pimpinan, 2, 'approve', $catatan);

            return $pengajuan->refresh();
        });

        $this->broadcastKeRole($fresh, ['guru', 'admin_tu'], 'approved');
        event(new PengajuanNomorGenerated($fresh));
        event(new PengajuanApproved($fresh, 2));

        return $fresh;
    }

    public function reject(PengajuanSurat $pengajuan, User $approver, string $catatan): void
    {
        $step = $this->validasiTahapApproval($pengajuan);

        $fresh = DB::transaction(function () use ($pengajuan, $approver, $catatan, $step) {
            $pengajuan->update([
                'status' => StatusPengajuan::DITOLAK,
                'reviewed_by' => $approver->id,
                'reviewed_at' => now(),
                'catatan_reviewer' => $catatan,
            ]);

            $this->catatLog($pengajuan, $approver, $step, 'reject', $catatan);

            return $pengajuan->refresh();
        });

        $this->broadcastKeRole($fresh, ['guru'], 'rejected');
        event(new PengajuanRejected($fresh));
    }

    public function mintaRevisi(PengajuanSurat $pengajuan, User $approver, string $catatan): void
    {
        $step = $this->validasiTahapApproval($pengajuan);

        $fresh = DB::transaction(function () use ($pengajuan, $approver, $catatan, $step) {
            $pengajuan->update([
                'status' => StatusPengajuan::DRAFT,
                'current_step' => 0,
                'reviewed_by' => $approver->id,
                'reviewed_at' => now(),
                'catatan_reviewer' => $catatan,
            ]);

            $this->catatLog($pengajuan, $approver, $step, 'revisi', $catatan);

            return $pengajuan->refresh();
        });

        $this->broadcastKeRole($fresh, ['guru'], 'revisi');
    }

    private function pastikanStep(PengajuanSurat $pengajuan, int $expectedStep): void
    {
        if ($pengajuan->current_step !== $expectedStep) {
            throw new \RuntimeException(
                "Aksi Ditolak: Pengajuan tidak dalam tahap yang sesuai (Expected: Step {$expectedStep}, Actual: Step {$pengajuan->current_step})."
            );
        }
    }

    private function validasiTahapApproval(PengajuanSurat $pengajuan): int
    {
        $step = $pengajuan->current_step;
        if (! in_array($step, [1, 2], true)) {
            throw new \RuntimeException('Aksi Ditolak: Pengajuan tidak berada dalam tahap approval yang valid.');
        }

        return $step;
    }

    private function catatLog(PengajuanSurat $pengajuan, User $user, int $step, string $aksi, ?string $catatan): void
    {
        ApprovalLog::create([
            'pengajuan_id' => $pengajuan->id,
            'step' => $step,
            'approver_id' => $user->id,
            'aksi' => $aksi,
            'catatan' => $catatan,
            'acted_at' => now(),
        ]);
    }

    private function broadcastKeRole(PengajuanSurat $pengajuan, array $roles, string $aksi): void
    {
        foreach ($roles as $role) {
            broadcast(new PengajuanStatusChanged($pengajuan, $role, $aksi))->toOthers();
        }
    }
}
