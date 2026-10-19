<?php

use App\Enums\StatusPengajuan;
use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Models\Pengaturan;
use App\Models\User;
use App\Services\NomorSuratService;
use Carbon\Carbon;
use Database\Seeders\BlankoSuratSeeder;
use Database\Seeders\PengaturanSeeder;

beforeEach(function () {
    $this->seed(BlankoSuratSeeder::class);
    $this->seed(PengaturanSeeder::class);
});

it('Generate Nomor Pengajuan Format PGJ/YYYY/MM/XXXX', function () {
    Carbon::setTestNow('2025-10-15 10:00:00');
    $service = new NomorSuratService;

    expect($service->generateNomorPengajuan())->toBe('PGJ/2025/10/0001');
});

it('Increment Nomor Pengajuan Sesuai Urutan', function () {
    Carbon::setTestNow('2025-10-15');
    $service = new NomorSuratService;
    $user = User::factory()->create();
    $blanko = BlankoSurat::first();

    foreach (range(1, 5) as $i) {
        PengajuanSurat::create([
            'nomor_pengajuan' => sprintf('PGJ/2025/10/%04d', $i),
            'user_id' => $user->id,
            'blanko_id' => $blanko->id,
            'tanggal_pelaksanaan' => now(),
            'nama_kegiatan' => "Test {$i}",
            'jenis_surat' => 'Test',
        ]);
    }

    expect($service->generateNomorPengajuan())->toBe('PGJ/2025/10/0006');
});

it('Reset Nomor Pengajuan Saat Ganti Bulan', function () {
    Carbon::setTestNow('2025-10-30');
    $service = new NomorSuratService;
    $user = User::factory()->create();
    $blanko = BlankoSurat::first();

    PengajuanSurat::create([
        'nomor_pengajuan' => 'PGJ/2025/10/0001',
        'user_id' => $user->id,
        'blanko_id' => $blanko->id,
        'tanggal_pelaksanaan' => now(),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
    ]);

    Carbon::setTestNow('2025-11-01');
    expect($service->generateNomorPengajuan())->toBe('PGJ/2025/11/0001');
});

it('Reset Nomor Pengajuan Saat Ganti Tahun', function () {
    Carbon::setTestNow('2025-12-30');
    $service = new NomorSuratService;
    $user = User::factory()->create();
    $blanko = BlankoSurat::first();

    PengajuanSurat::create([
        'nomor_pengajuan' => 'PGJ/2025/12/0001',
        'user_id' => $user->id,
        'blanko_id' => $blanko->id,
        'tanggal_pelaksanaan' => now(),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
    ]);

    Carbon::setTestNow('2026-01-01');
    expect($service->generateNomorPengajuan())->toBe('PGJ/2026/01/0001');
});

it('Generate Nomor Surat Dengan Format Configurable', function () {
    Carbon::setTestNow('2025-10-15');
    $service = new NomorSuratService;
    Pengaturan::updateOrCreate(
        ['key' => 'surat.format_nomor'],
        ['value' => '{seq}/{kode}/{unit}/{romawi}/{tahun}'],
    );
    Pengaturan::updateOrCreate(['key' => 'surat.kode_unit'], ['value' => 'TU']);
    Pengaturan::updateOrCreate(['key' => 'surat.reset_seq'], ['value' => 'tahunan']);

    $nomor = $service->generateNomorSurat('ST');
    expect($nomor)->toBe('001/ST/TU/X/2025');
});

it('Nomor Surat Tidak Boleh Duplicate', function () {
    Carbon::setTestNow('2025-10-15');
    $service = new NomorSuratService;
    $user = User::factory()->create();
    $blanko = BlankoSurat::first();

    $nomorList = [];
    foreach (range(1, 3) as $i) {
        $nomor = $service->generateNomorSurat('ST');

        PengajuanSurat::create([
            'nomor_pengajuan' => 'PGJ/2025/10/'.str_pad($i, 4, '0', STR_PAD_LEFT),
            'nomor_surat' => $nomor,
            'user_id' => $user->id,
            'blanko_id' => $blanko->id,
            'tanggal_pelaksanaan' => now(),
            'nama_kegiatan' => "Test {$i}",
            'jenis_surat' => 'Test',
            'status' => StatusPengajuan::DISETUJUI,
        ]);

        $nomorList[] = $nomor;
    }

    expect($nomorList)->toBe([
        '001/ST/TU/X/2025',
        '002/ST/TU/X/2025',
        '003/ST/TU/X/2025',
    ]);
});
