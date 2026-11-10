<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\PengajuanSurat;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengajuanNomorGenerated
{
    use Dispatchable, SerializesModels;

    public readonly string $nomorSurat;

    public readonly string $namaKegiatan;

    public function __construct(public readonly PengajuanSurat $pengajuan)
    {
        $this->nomorSurat = $pengajuan->nomor_surat ?? 'Belum Ada Nomor';
        $this->namaKegiatan = $pengajuan->nama_kegiatan;
    }
}
