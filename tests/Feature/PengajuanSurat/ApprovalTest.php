<?php

use App\Enums\StatusPengajuan;
use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BlankoSuratSeeder;
use Database\Seeders\PengaturanSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;

/**
 * @return array{guru: User, admin: User, pimpinan: User, pengajuan: PengajuanSurat}
 */
function createApprovalTestData(): array
{
    (new RoleSeeder)->run();
    (new PermissionSeeder)->run();
    (new RolePermissionSeeder)->run();
    (new BlankoSuratSeeder)->run();
    (new PengaturanSeeder)->run();

    $guru = User::factory()->create(['role_id' => Role::where('name', 'guru')->value('id')]);
    $admin = User::factory()->create(['role_id' => Role::where('name', 'admin_tu')->value('id')]);
    $pimpinan = User::factory()->create(['role_id' => Role::where('name', 'pimpinan')->value('id')]);
    $blanko = BlankoSurat::query()->firstOrFail();
    $pengajuan = PengajuanSurat::factory()->create([
        'user_id' => $guru->id,
        'blanko_id' => $blanko->id,
        'status' => StatusPengajuan::DIAJUKAN,
        'current_step' => 1,
    ]);

    return compact('guru', 'admin', 'pimpinan', 'pengajuan');
}

it('Admin Bisa Verifikasi Pengajuan → Lanjut ke Pimpinan', function () {
    $data = createApprovalTestData();
    $admin = $data['admin'];
    $pengajuan = $data['pengajuan'];

    $this->actingAs($admin)->post(route('approval.verify', $pengajuan), ['catatan' => 'OK']);

    $pengajuan->refresh();
    expect($pengajuan->status)->toBe(StatusPengajuan::DIPROSES)->and($pengajuan->current_step)->toBe(2);

    $this->assertDatabaseHas('approval_log', [
        'pengajuan_id' => $pengajuan->id,
        'step' => 1,
        'aksi' => 'approve',
        'approver_id' => $admin->id,
    ]);
});

it('Pimpinan Approve → Generate Nomor Surat & QR', function () {
    $data = createApprovalTestData();
    $admin = $data['admin'];
    $pimpinan = $data['pimpinan'];
    $pengajuan = $data['pengajuan'];

    $this->actingAs($admin)->post(route('approval.verify', $pengajuan), ['catatan' => 'OK']);

    $this->actingAs($pimpinan)->post(route('approval.approve', $pengajuan->fresh()), ['catatan' => 'Setuju']);

    $p = $pengajuan->fresh();
    expect($p->status)->toBe(StatusPengajuan::DISETUJUI)->and($p->current_step)->toBe(3)->and($p->nomor_surat)->not->toBeNull()->and($p->qr_token)->not->toBeNull();
});

it('Admin Tidak Bisa Approve Langsung Tanpa Verifikasi', function () {
    $data = createApprovalTestData();

    $this->actingAs($data['admin'])->post(route('approval.approve', $data['pengajuan']))->assertForbidden();
});

it('Guru Tidak Bisa Akses Halaman Approval', function () {
    $data = createApprovalTestData();

    $this->actingAs($data['guru'])->get(route('approval.index'))->assertForbidden();
});

it('Reject Mengubah Status Jadi DITOLAK', function () {
    $data = createApprovalTestData();

    $this->actingAs($data['admin'])->post(route('approval.reject', $data['pengajuan']), ['catatan' => 'Kurang Lengkap']);

    expect($data['pengajuan']->fresh()->status)->toBe(StatusPengajuan::DITOLAK);
});
