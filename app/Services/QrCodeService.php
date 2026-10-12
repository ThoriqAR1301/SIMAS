<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateToken(): string
    {
        return (string) Str::uuid();
    }

    public function generateQrImage(string $token, string $folder = 'qr'): string
    {
        $url = route('verifikasi.publik', ['token' => $token]);

        $filename = "{$folder}/{$token}.png";
        $png = QrCode::format('png')->size(300)->margin(2)->errorCorrection('M')->generate($url);

        Storage::disk('public')->put($filename, $png);

        return $filename;
    }
}