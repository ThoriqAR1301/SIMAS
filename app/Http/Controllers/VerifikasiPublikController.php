<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;

class VerifikasiPublikController extends Controller
{
    public function show(string $token)
    {
        $pengajuan = PengajuanSurat::with(['user', 'blanko'])->where('qr_token', $token)->firstOrFail();

        return view('verifikasi.show', compact('pengajuan'));
    }
}