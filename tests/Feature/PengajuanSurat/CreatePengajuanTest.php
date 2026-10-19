<?php

use App\Enums\StatusPengajuan;
use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BlankoSuratSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;

/**
 * @return array{guru: User, blanko: BlankoSurat}
 */
function createPengajuanCreateTestData(): array
{
    (new RoleSeeder)->run();
    (new PermissionSeeder)->run();
    (new RolePermissionSeeder)->run();
    (new BlankoSuratSeeder)->run();

    $guru = User::factory()->create([
        'role_id' => Role::where('name', 'guru')->value('id'),
    ]);

    return [
        'guru' => $guru,
        'blanko' => BlankoSurat::query()->firstOrFail(),
    ];
}

it('Guru Bisa Membuat Pengajuan Sebagai Draft', function () {
    $data = createPengajuanCreateTestData();
    $guru = $data['guru'];
    $blanko = $data['blanko'];

    $response = $this->actingAs($guru)->post(route('pengajuan.store'), [
        'blanko_id' => $blanko->id,
        'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
        'nama_kegiatan' => 'Rapat Dinas',
        'jenis_surat' => 'Surat Tugas',
        'aksi' => 'draft',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('pengajuan_surat', [
        'user_id' => $guru->id,
        'nama_kegiatan' => 'Rapat Dinas',
        'status' => StatusPengajuan::DRAFT->value,
        'current_step' => 0,
    ]);
});

it('Guru Bisa Submit Langsung Saat Create', function () {
    $data = createPengajuanCreateTestData();
    $guru = $data['guru'];
    $blanko = $data['blanko'];

    $this->actingAs($guru)->post(route('pengajuan.store'), [
        'blanko_id' => $blanko->id,
        'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
        'nama_kegiatan' => 'Rapat Dinas',
        'jenis_surat' => 'Surat Tugas',
        'aksi' => 'submit',
    ]);

    $p = PengajuanSurat::query()->latest()->firstOrFail();
    expect($p->status)->toBe(StatusPengajuan::DIAJUKAN)->and($p->current_step)->toBe(1);
});

it('Validasi : Tanggal Pelaksanaan Tidak Boleh Di Masa Lalu', function () {
    $data = createPengajuanCreateTestData();
    $guru = $data['guru'];
    $blanko = $data['blanko'];

    $response = $this->actingAs($guru)->post(route('pengajuan.store'), [
        'blanko_id' => $blanko->id,
        'tanggal_pelaksanaan' => now()->subDays(1)->format('Y-m-d'),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
    ]);

    $response->assertSessionHasErrors('tanggal_pelaksanaan');
});

it('nomor pengajuan auto-generate dengan format yang benar', function () {
    $data = createPengajuanCreateTestData();
    $guru = $data['guru'];
    $blanko = $data['blanko'];

    $this->actingAs($guru)->post(route('pengajuan.store'), [
        'blanko_id' => $blanko->id,
        'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
    ]);

    $p = PengajuanSurat::query()->latest()->firstOrFail();
    expect($p->nomor_pengajuan)->toMatch('/^PGJ\/\d{4}\/\d{2}\/\d{4}$/');
});
