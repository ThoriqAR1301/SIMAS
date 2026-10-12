<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;

class VerifikasiSuratController extends Controller
{
    public function show(string $token)
    {
        $pengajuan = PengajuanSurat::query()
            ->with('user')
            ->where('qr_token', $token)
            ->whereNotNull('nomor_surat')
            ->whereIn('status', [
                StatusPengajuan::DISETUJUI->value,
                StatusPengajuan::SELESAI->value,
            ])
            ->firstOrFail();

        return view('verifikasi.show', compact('pengajuan'));
    }
}
