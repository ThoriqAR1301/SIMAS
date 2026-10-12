<?php

namespace App\Services;

use App\Enums\StatusPengajuan;
use App\Events\PengajuanApproved;
use App\Events\PengajuanNomorGenerated;
use App\Events\PengajuanRejected;
use App\Models\ApprovalLog;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ApprovalService
{
    public function __construct(
        private NomorSuratService $nomorService,
        private QrCodeService $qrService,
    ) {}

    public function verifikasiAdmin(
        PengajuanSurat $pengajuan,
        User $admin,
        ?string $catatan = null,
    ): void {
        $this->pastikanStep($pengajuan, 1);

        DB::transaction(function () use ($pengajuan, $admin, $catatan) {
            $pengajuan->update([
                'status' => StatusPengajuan::DIPROSES,
                'current_step' => 2,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'catatan_reviewer' => $catatan,
            ]);

            ApprovalLog::create([
                'pengajuan_id' => $pengajuan->id,
                'step' => 1,
                'approver_id' => $admin->id,
                'aksi' => 'approve',
                'catatan' => $catatan,
                'acted_at' => now(),
            ]);
        });

        event(new PengajuanApproved($pengajuan->fresh(), 1));
    }

    public function approvePimpinan(
        PengajuanSurat $pengajuan,
        User $pimpinan,
        ?string $catatan = null,
    ): PengajuanSurat {
        $this->pastikanStep($pengajuan, 2);

        return DB::transaction(function () use ($pengajuan, $pimpinan, $catatan) {
            $nomorSurat = $this->nomorService->generateNomorSurat($pengajuan->blanko->kode);
            $qrToken = $this->qrService->generateToken();

            $pengajuan->update([
                'nomor_surat' => $nomorSurat,
                'status' => StatusPengajuan::DISETUJUI,
                'current_step' => 3,
                'reviewed_by' => $pimpinan->id,
                'reviewed_at' => now(),
                'catatan_reviewer' => $catatan,
                'qr_token' => $qrToken,
                'qr_generated_at' => now(),
            ]);

            $this->qrService->generateQrImage($qrToken);

            ApprovalLog::create([
                'pengajuan_id' => $pengajuan->id,
                'step' => 2,
                'approver_id' => $pimpinan->id,
                'aksi' => 'approve',
                'catatan' => $catatan,
                'acted_at' => now(),
            ]);

            $fresh = $pengajuan->fresh();

            event(new PengajuanNomorGenerated($fresh));
            event(new PengajuanApproved($fresh, 2));

            return $fresh;
        });
    }

    public function reject(
        PengajuanSurat $pengajuan,
        User $approver,
        string $catatan,
    ): void {
        $step = $pengajuan->current_step;
        if (! in_array($step, [1, 2])) {
            throw new \RuntimeException('Pengajuan Tidak Dalam Tahap Approval');
        }

        DB::transaction(function () use ($pengajuan, $approver, $catatan, $step) {
            $pengajuan->update([
                'status' => StatusPengajuan::DITOLAK,
                'reviewed_by' => $approver->id,
                'reviewed_at' => now(),
                'catatan_reviewer' => $catatan,
            ]);

            ApprovalLog::create([
                'pengajuan_id' => $pengajuan->id,
                'step' => $step,
                'approver_id' => $approver->id,
                'aksi' => 'reject',
                'catatan' => $catatan,
                'acted_at' => now(),
            ]);
        });

        event(new PengajuanRejected($pengajuan->fresh()));
    }

    public function mintaRevisi(
        PengajuanSurat $pengajuan,
        User $approver,
        string $catatan,
    ): void {
        $step = $pengajuan->current_step;
        if (! in_array($step, [1, 2])) {
            throw new \RuntimeException('Pengajuan Tidak Dalam Tahap Approval');
        }

        DB::transaction(function () use ($pengajuan, $approver, $catatan, $step) {
            $pengajuan->update([
                'status' => StatusPengajuan::DRAFT,
                'current_step' => 0,
                'reviewed_by' => $approver->id,
                'reviewed_at' => now(),
                'catatan_reviewer' => $catatan,
            ]);

            ApprovalLog::create([
                'pengajuan_id' => $pengajuan->id,
                'step' => $step,
                'approver_id' => $approver->id,
                'aksi' => 'revisi',
                'catatan' => $catatan,
                'acted_at' => now(),
            ]);
        });
    }

    private function pastikanStep(PengajuanSurat $pengajuan, int $expectedStep): void
    {
        if ($pengajuan->current_step !== $expectedStep) {
            throw new \RuntimeException(
                "Pengajuan Tidak Dalam Tahap yang Sesuai (expected step {$expectedStep}, got {$pengajuan->current_step})."
            );
        }
    }
}
