<?php

declare(strict_types=1);

use App\Enums\StatusPengajuan;
use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Models\Pengaturan;
use App\Models\Role;
use App\Models\User;
use App\Services\NomorSuratService;

use Database\Seeders\BlankoSuratSeeder;
use Database\Seeders\PengaturanSeeder;
use Database\Seeders\RoleSeeder;

/** @return array{service: NomorSuratService, buatPengajuan: \Closure} */
function buatKonteksNomorSurat(): array
{
    app(BlankoSuratSeeder::class)->run();
    app(PengaturanSeeder::class)->run();
    app(RoleSeeder::class)->run();

    $service = new NomorSuratService();
    $user = User::factory()->create([
        'role_id' => Role::query()->where('name', 'guru')->value('id'),
    ]);

    $blanko = BlankoSurat::firstOrFail();

    $buatPengajuan = function (string $nomorPengajuan, ?string $nomorSurat = null, string $jenis = 'Test') use ($user, $blanko): PengajuanSurat {
        return PengajuanSurat::create([
            'nomor_pengajuan' => $nomorPengajuan,
            'nomor_surat' => $nomorSurat,
            'user_id' => $user->id,
            'blanko_id' => $blanko->id,
            'tanggal_pelaksanaan' => now(),
            'nama_kegiatan' => "Test " . fake()->word(),
            'jenis_surat' => $jenis,
            'status' => StatusPengajuan::DISETUJUI,
        ]);
    };

    return compact('service', 'buatPengajuan');
}

describe('Nomor Pengajuan Internal', function () {

    it('dapat generate format PGJ/YYYY/MM/XXXX', function () {
        $context = buatKonteksNomorSurat();
        $this->travelTo('2025-10-15 10:00:00');

        expect($context['service']->generateNomorPengajuan())
            ->toBe('PGJ/2025/10/0001');
    });

    it('increment nomor sesuai urutan', function () {
        $context = buatKonteksNomorSurat();
        $this->travelTo('2025-10-15');

        foreach (range(1, 5) as $i) {
            ($context['buatPengajuan'])(sprintf('PGJ/2025/10/%04d', $i));
        }

        expect($context['service']->generateNomorPengajuan())
            ->toBe('PGJ/2025/10/0006');
    });

    it('reset nomor saat ganti bulan', function () {
        $context = buatKonteksNomorSurat();
        $this->travelTo('2025-10-30');
        ($context['buatPengajuan'])('PGJ/2025/10/0001');

        $this->travelTo('2025-11-01');
        expect($context['service']->generateNomorPengajuan())
            ->toBe('PGJ/2025/11/0001');
    });

    it('reset nomor saat ganti tahun', function () {
        $context = buatKonteksNomorSurat();
        $this->travelTo('2025-12-30');
        ($context['buatPengajuan'])('PGJ/2025/12/0001');

        $this->travelTo('2026-01-01');
        expect($context['service']->generateNomorPengajuan())
            ->toBe('PGJ/2026/01/0001');
    });
});

describe('Nomor Surat Resmi', function () {

    it('dapat generate nomor surat dengan format configurable', function () {
        $context = buatKonteksNomorSurat();
        $this->travelTo('2025-10-15');

        Pengaturan::set('surat.format_nomor', '{seq}/{kode}/{unit}/{romawi}/{tahun}');
        Pengaturan::set('surat.kode_unit', 'TU');
        Pengaturan::set('surat.reset_seq', 'tahunan');

        $nomor = $context['service']->generateNomorSurat('ST');
        
        expect($nomor)->toBe('001/ST/TU/X/2025');
    });

    it('tidak memperbolehkan nomor surat duplicate', function () {
        $context = buatKonteksNomorSurat();
        $this->travelTo('2025-10-15');

        Pengaturan::set('surat.format_nomor', '{seq}/{kode}/{unit}/{romawi}/{tahun}');
        Pengaturan::set('surat.kode_unit', 'TU');
        Pengaturan::set('surat.reset_seq', 'tahunan');

        $nomorList = [];

        foreach (range(1, 3) as $i) {
            $nomor = $context['service']->generateNomorSurat('ST');
            
            ($context['buatPengajuan'])(
                'PGJ/2025/10/' . str_pad((string)$i, 4, '0', STR_PAD_LEFT), 
                $nomor
            );

            $nomorList[] = $nomor;
        }

        expect($nomorList)->toBe([
            '001/ST/TU/X/2025',
            '002/ST/TU/X/2025',
            '003/ST/TU/X/2025',
        ]);
    });
});