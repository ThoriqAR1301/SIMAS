<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\PengajuanSurat;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengajuanSubmitted
{
    use Dispatchable, SerializesModels;

    public readonly string $nomorPengajuan;

    public readonly string $namaKegiatan;

    public readonly string $namaPengaju;

    public readonly string $jenisSurat;

    public readonly string $waktuPengajuan;

    public function __construct(public readonly PengajuanSurat $pengajuan)
    {
        $pengajuan->loadMissing('user');

        $this->nomorPengajuan = $pengajuan->nomor_pengajuan;
        $this->namaKegiatan = $pengajuan->nama_kegiatan;
        $this->namaPengaju = $pengajuan->user->name ?? 'Sistem';
        $this->jenisSurat = $pengajuan->jenis_surat;
        $this->waktuPengajuan = now()->translatedFormat('d F Y H:i');
    }

    public function toArray(): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'nomor_pengajuan' => $this->nomorPengajuan,
            'kegiatan' => $this->namaKegiatan,
            'pengaju' => $this->namaPengaju,
            'jenis_surat' => $this->jenisSurat,
            'waktu' => $this->waktuPengajuan,
        ];
    }

    public function getNotificationMessage(): string
    {
        return "{$this->namaPengaju} telah mengirimkan pengajuan baru: {$this->namaKegiatan}.";
    }
}
