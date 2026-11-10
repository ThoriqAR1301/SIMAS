<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\PengajuanSurat;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengajuanRejected
{
    use Dispatchable, SerializesModels;

    public readonly string $namaPengaju;

    public readonly string $namaKegiatan;

    public readonly string $ditolakOleh;

    public readonly ?string $alasanPenolakan;

    public readonly string $waktuPenolakan;

    public function __construct(public readonly PengajuanSurat $pengajuan)
    {
        $pengajuan->loadMissing(['user', 'reviewer']);

        $this->namaPengaju = $pengajuan->user->name ?? 'Sistem';
        $this->namaKegiatan = $pengajuan->nama_kegiatan;
        $this->ditolakOleh = $pengajuan->reviewer->name ?? 'Sistem';
        $this->alasanPenolakan = $pengajuan->catatan_reviewer;
        $this->waktuPenolakan = now()->translatedFormat('d F Y H:i');
    }

    public function toArray(): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->pengajuan->nomor_pengajuan,
            'kegiatan' => $this->namaKegiatan,
            'pengaju' => $this->namaPengaju,
            'ditolak_oleh' => $this->ditolakOleh,
            'alasan' => $this->alasanPenolakan,
            'waktu' => $this->waktuPenolakan,
        ];
    }

    public function hasReason(): bool
    {
        return ! empty($this->alasanPenolakan);
    }
}
