<?php

use App\Enums\StatusPengajuan;
use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Models\User;

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
    $this->seed(\Database\Seeders\PermissionSeeder::class);
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->seed(\Database\Seeders\BlankoSuratSeeder::class);

    $this->guru = User::factory()->create([
        'role_id' => \App\Models\Role::where('name', 'guru')->value('id'),
    ]);
    $this->blanko = BlankoSurat::first();
});

it('Guru Bisa Membuat Pengajuan Sebagai Draft', function () {
    $response = $this->actingAs($this->guru)->post(route('pengajuan.store'), [
        'blanko_id' => $this->blanko->id,
        'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
        'nama_kegiatan' => 'Rapat Dinas',
        'jenis_surat' => 'Surat Tugas',
        'aksi' => 'draft',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('pengajuan_surat', [
        'user_id' => $this->guru->id,
        'nama_kegiatan' => 'Rapat Dinas',
        'status' => StatusPengajuan::DRAFT->value,
        'current_step'  => 0,
    ]);
});

it('Guru Bisa Submit Langsung Saat Create', function () {
    $this->actingAs($this->guru)->post(route('pengajuan.store'), [
        'blanko_id' => $this->blanko->id,
        'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
        'nama_kegiatan' => 'Rapat Dinas',
        'jenis_surat' => 'Surat Tugas',
        'aksi' => 'submit',
    ]);

    $p = PengajuanSurat::latest()->first();
    expect($p->status)->toBe(StatusPengajuan::DIAJUKAN)->and($p->current_step)->toBe(1);
});

it('Validasi : Tanggal Pelaksanaan Tidak Boleh Di Masa Lalu', function () {
    $response = $this->actingAs($this->guru)->post(route('pengajuan.store'), [
        'blanko_id' => $this->blanko->id,
        'tanggal_pelaksanaan' => now()->subDays(1)->format('Y-m-d'),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
    ]);

    $response->assertSessionHasErrors('tanggal_pelaksanaan');
});

it('nomor pengajuan auto-generate dengan format yang benar', function () {
    $this->actingAs($this->guru)->post(route('pengajuan.store'), [
        'blanko_id' => $this->blanko->id,
        'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
    ]);

    $p = PengajuanSurat::latest()->first();
    expect($p->nomor_pengajuan)->toMatch('/^PGJ\/\d{4}\/\d{2}\/\d{4}$/');
});