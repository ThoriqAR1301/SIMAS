<?php

use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Services\NomorSuratService;
use Carbon\Carbon;

it('Generate Nomor Pengajuan Dengan Format PGJ/YYYY/MM/XXXX', function () {
    Carbon::setTestNow('2025-10-15');
    $this->seed(\Database\Seeders\BlankoSuratSeeder::class);

    $service = new NomorSuratService();
    $nomor = $service->generateNomorPengajuan();

    expect($nomor)->toBe('PGJ/2025/10/0001');
});

it('Increment Nomor Pengajuan Jika Sudah Ada', function () {
    Carbon::setTestNow('2025-10-15');
    $this->seed(\Database\Seeders\BlankoSuratSeeder::class);

    $user = \App\Models\User::factory()->create();
    PengajuanSurat::create([
        'nomor_pengajuan' => 'PGJ/2025/10/0001',
        'user_id' => $user->id,
        'blanko_id' => BlankoSurat::first()->id,
        'tanggal_pelaksanaan' => now(),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
    ]);

    $service = new NomorSuratService();
    expect($service->generateNomorPengajuan())->toBe('PGJ/2025/10/0002');
});