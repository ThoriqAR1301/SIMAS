<?php

use App\Enums\StatusPengajuan;

it('Renders A Pengajuan Status Badge For An Enum Status', function () {
    $this->blade('<x-pengajuan.status-badge :status="$status" />', [
        'status' => StatusPengajuan::DIAJUKAN,
    ])->assertSee('Menunggu Verifikasi');
});
