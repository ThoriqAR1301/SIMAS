<?php

use App\Enums\StatusPengajuan;
use App\Models\ApprovalLog;
use App\Models\PengajuanSurat;
use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    $this->seed(\Database\Seeders\RoleSeeder::class);
    $this->seed(\Database\Seeders\PermissionSeeder::class);
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->seed(\Database\Seeders\BlankoSuratSeeder::class);
    $this->seed(\Database\Seeders\PengaturanSeeder::class);

    $this->guru = User::factory()->create(['role_id' => Role::where('name', 'guru')->value('id')]);
    $this->admin = User::factory()->create(['role_id' => Role::where('name', 'admin_tu')->value('id')]);
    $this->pimpinan = User::factory()->create(['role_id' => Role::where('name', 'pimpinan')->value('id')]);

    $this->pengajuan = PengajuanSurat::factory()->create([
        'user_id' => $this->guru->id,
        'blanko_id' => \App\Models\BlankoSurat::first()->id,
        'status' => StatusPengajuan::DIAJUKAN,
        'current_step' => 1,
    ]);
});

it('Admin Bisa Verifikasi Pengajuan → Lanjut ke Pimpinan', function () {
    $this->actingAs($this->admin)->post(route('approval.verify', $this->pengajuan), ['catatan' => 'OK']);

    $this->pengajuan->refresh();
    expect($this->pengajuan->status)->toBe(StatusPengajuan::DIPROSES)->and($this->pengajuan->current_step)->toBe(2);

    $this->assertDatabaseHas('approval_log', [
        'pengajuan_id' => $this->pengajuan->id,
        'step' => 1,
        'aksi' => 'approve',
        'approver_id' => $this->admin->id,
    ]);
});

it('Pimpinan Approve → Generate Nomor Surat & QR', function () {
    $this->actingAs($this->admin)->post(route('approval.verify', $this->pengajuan), ['catatan' => 'OK']);

    $this->actingAs($this->pimpinan)->post(route('approval.approve', $this->pengajuan->fresh()), ['catatan' => 'Setuju']);

    $p = $this->pengajuan->fresh();
    expect($p->status)->toBe(StatusPengajuan::DISETUJUI)->and($p->current_step)->toBe(3)->and($p->nomor_surat)->not->toBeNull()->and($p->qr_token)->not->toBeNull();
});

it('Admin Tidak Bisa Approve Langsung Tanpa Verifikasi', function () {
    $this->actingAs($this->admin)->post(route('approval.approve', $this->pengajuan))->assertForbidden();
});

it('Guru Tidak Bisa Akses Halaman Approval', function () {
    $this->actingAs($this->guru)->get(route('approval.index'))->assertForbidden();
});

it('Reject Mengubah Status Jadi DITOLAK', function () {
    $this->actingAs($this->admin)->post(route('approval.reject', $this->pengajuan), ['catatan' => 'Kurang Lengkap']);

    expect($this->pengajuan->fresh()->status)->toBe(StatusPengajuan::DITOLAK);
});