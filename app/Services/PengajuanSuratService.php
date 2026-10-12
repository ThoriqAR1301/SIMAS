<?php

namespace App\Services;

use App\Enums\StatusPengajuan;
use App\Events\PengajuanSubmitted;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PengajuanSuratService
{
    public function __construct(
        private NomorSuratService  $nomorService,
        private FileUploadService  $fileService,
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
            ]);

            if (!empty($data['lampiran'])) {
                $this->simpanLampiran($pengajuan, $data['lampiran']);
            }

            return $pengajuan;
        });
    }

    public function update(PengajuanSurat $pengajuan, array $data): PengajuanSurat
    {
        if (!$pengajuan->bisaDiedit()) {
            throw new \RuntimeException('Pengajuan Tidak Bisa Diedit Pada Status Ini');
        }

        return DB::transaction(function () use ($pengajuan, $data) {
            $pengajuan->update([
                'blanko_id' => $data['blanko_id'] ?? $pengajuan->blanko_id,
                'tanggal_pelaksanaan' => $data['tanggal_pelaksanaan'] ?? $pengajuan->tanggal_pelaksanaan,
                'waktu_mulai' => $data['waktu_mulai'] ?? $pengajuan->waktu_mulai,
                'waktu_selesai' => $data['waktu_selesai'] ?? $pengajuan->waktu_selesai,
                'tempat' => $data['tempat'] ?? $pengajuan->tempat,
                'nama_kegiatan' => $data['nama_kegiatan'] ?? $pengajuan->nama_kegiatan,
                'jenis_surat' => $data['jenis_surat'] ?? $pengajuan->jenis_surat,
                'keterangan' => $data['keterangan'] ?? $pengajuan->keterangan,
            ]);

            if (!empty($data['lampiran'])) {
                $this->simpanLampiran($pengajuan, $data['lampiran']);
            }

            return $pengajuan->fresh();
        });
    }

    public function submit(PengajuanSurat $pengajuan): PengajuanSurat
    {
        if (!$pengajuan->bisaDiajukan()) {
            throw new \RuntimeException('Pengajuan Tidak Bisa Diajukan Pada Status Ini');
        }

        DB::transaction(function () use ($pengajuan) {
            $pengajuan->update([
                'status' => StatusPengajuan::DIAJUKAN,
                'current_step' => 1,  
                'reviewed_by' => null,
                'reviewed_at' => null,
                'catatan_reviewer' => null,
            ]);
        });

        event(new PengajuanSubmitted($pengajuan->fresh()));

        return $pengajuan->fresh();
    }

    public function hapus(PengajuanSurat $pengajuan): void
    {
        if (!$pengajuan->bisaDihapus()) {
            throw new \RuntimeException('Pengajuan Tidak Bisa Dihapus Pada Status Ini');
        }

        DB::transaction(function () use ($pengajuan) {
            foreach ($pengajuan->lampiran as $lap) {
                $this->fileService->hapus($lap->path);
            }
            $pengajuan->lampiran()->delete();
            $pengajuan->delete();
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