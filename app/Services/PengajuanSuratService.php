<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\StatusPengajuan;
use App\Events\PengajuanActivityChanged;
use App\Events\PengajuanStatusChanged;
use App\Events\PengajuanSubmitted;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PengajuanSuratService
{
    public function __construct(
        private readonly NomorSuratService $nomorService,
        private readonly FileUploadService $fileService,
        private readonly LogAktivitasService $logService,
    ) {}

    public function buat(User $user, array $data): PengajuanSurat
    {
        return DB::transaction(function () use ($user, $data) {
            $pengajuan = PengajuanSurat::create([
                'nomor_pengajuan' => $this->nomorService->generateNomorPengajuan(),
                'user_id' => $user->id,
                'blanko_id' => $data['blanko_id'],
                'tanggal_pelaksanaan' => $data['tanggal_pelaksanaan'],
                'waktu_mulai' => $data['waktu_mulai'] ?? null,
                'waktu_selesai' => $data['waktu_selesai'] ?? null,
                'tempat' => $data['tempat'] ?? null,
                'nama_kegiatan' => $data['nama_kegiatan'],
                'jenis_surat' => $data['jenis_surat'],
                'keterangan' => $data['keterangan'] ?? null,
                'status' => StatusPengajuan::DRAFT,
                'current_step' => 0,
                'panitia' => $data['panitia'] ?? [],
            ]);

            if (! empty($data['lampiran'])) {
                $this->simpanLampiran($pengajuan, $data['lampiran']);
            }

            return $pengajuan;
        });
    }

    public function update(PengajuanSurat $pengajuan, array $data): PengajuanSurat
    {
        if (! $pengajuan->bisaDiedit()) {
            throw new RuntimeException('Gagal Memperbarui: Pengajuan tidak bisa diedit pada status ini.');
        }

        return DB::transaction(function () use ($pengajuan, $data) {
            $before = $pengajuan->toArray();

            $pengajuan->fill([
                'blanko_id' => $data['blanko_id'] ?? $pengajuan->blanko_id,
                'tanggal_pelaksanaan' => $data['tanggal_pelaksanaan'] ?? $pengajuan->tanggal_pelaksanaan,
                'waktu_mulai' => $data['waktu_mulai'] ?? $pengajuan->waktu_mulai,
                'waktu_selesai' => $data['waktu_selesai'] ?? $pengajuan->waktu_selesai,
                'tempat' => $data['tempat'] ?? $pengajuan->tempat,
                'nama_kegiatan' => $data['nama_kegiatan'] ?? $pengajuan->nama_kegiatan,
                'jenis_surat' => $data['jenis_surat'] ?? $pengajuan->jenis_surat,
                'keterangan' => $data['keterangan'] ?? $pengajuan->keterangan,
                'panitia' => $data['panitia'] ?? $pengajuan->panitia,
            ]);

            if ($pengajuan->isDirty()) {
                $pengajuan->save();
            }

            if (! empty($data['lampiran'])) {
                $this->simpanLampiran($pengajuan, $data['lampiran']);
            }

            $fresh = $pengajuan->fresh();

            $this->logService->catat('update_pengajuan', $fresh, $before, $fresh->toArray());

            DB::afterCommit(function () use ($fresh) {
                broadcast(new PengajuanActivityChanged(
                    $fresh,
                    'updated',
                    route('pengajuan.show', $fresh),
                ))->toOthers();
            });

            return $fresh;
        });
    }

    public function submit(PengajuanSurat $pengajuan): PengajuanSurat
    {
        if (! $pengajuan->bisaDiajukan()) {
            throw new RuntimeException('Gagal Mengajukan: Pengajuan tidak bisa diajukan pada status ini.');
        }

        $fresh = DB::transaction(function () use ($pengajuan) {
            $pengajuan->update([
                'status' => StatusPengajuan::DIAJUKAN,
                'current_step' => 1,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'catatan_reviewer' => null,
            ]);

            return $pengajuan->fresh();
        });

        DB::afterCommit(function () use ($fresh) {
            event(new PengajuanSubmitted($fresh));
            broadcast(new PengajuanStatusChanged($fresh, 'admin_tu', 'submitted'))->toOthers();
        });

        return $fresh;
    }

    public function hapus(PengajuanSurat $pengajuan): void
    {
        if (! $pengajuan->bisaDihapus()) {
            throw new RuntimeException('Gagal Menghapus: Pengajuan tidak bisa dihapus pada status ini.');
        }

        DB::transaction(function () use ($pengajuan) {
            $this->logService->catat('hapus_pengajuan', $pengajuan, $pengajuan->toArray());

            foreach ($pengajuan->lampiran as $lap) {
                $this->fileService->hapus($lap->path);
            }

            $pengajuan->lampiran()->delete();
            $pengajuan->delete();

            DB::afterCommit(function () use ($pengajuan) {
                broadcast(new PengajuanActivityChanged(
                    $pengajuan,
                    'deleted',
                    route('pengajuan.index'),
                ))->toOthers();
            });
        });
    }

    private function simpanLampiran(PengajuanSurat $pengajuan, array $files): void
    {
        $metadata = $this->fileService->simpanBanyak($files, 'lampiran/pengajuan');

        foreach ($metadata as $meta) {
            $pengajuan->lampiran()->create($meta);
        }
    }
}
