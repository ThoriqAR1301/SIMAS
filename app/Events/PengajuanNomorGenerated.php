<?php

namespace App\Events;

use App\Models\PengajuanSurat;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengajuanNomorGenerated
{
    use Dispatchable, SerializesModels;

    public function __construct(public PengajuanSurat $pengajuan) {}
}
