<?php

use App\Enums\StatusPengajuan;
use App\Events\PengajuanApproved;
use App\Events\PengajuanNomorGenerated;
use App\Events\PengajuanStatusChanged;
use App\Events\PengajuanSubmitted;
use App\Models\ApprovalLog;
use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BlankoSuratSeeder;
use Database\Seeders\PengaturanSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

/**
 * @return array{guru: User, admin: User, pimpinan: User, blanko: BlankoSurat}
 */
function createFullApprovalFlowTestData(): array
{
    (new RoleSeeder)->run();
    (new PermissionSeeder)->run();
    (new RolePermissionSeeder)->run();
    (new BlankoSuratSeeder)->run();
    (new PengaturanSeeder)->run();

    return [
        'guru' => User::factory()->create(['role_id' => Role::where('name', 'guru')->value('id')]),
        'admin' => User::factory()->create(['role_id' => Role::where('name', 'admin_tu')->value('id')]),
        'pimpinan' => User::factory()->create(['role_id' => Role::where('name', 'pimpinan')->value('id')]),
        'blanko' => BlankoSurat::query()->firstOrFail(),
    ];
}

it('Full Flow : Guru Submit → Admin Verify → Pimpinan Approve', function () {
    $data = createFullApprovalFlowTestData();
    $guru = $data['guru'];
    $admin = $data['admin'];
    $pimpinan = $data['pimpinan'];
    $blanko = $data['blanko'];

    Event::fake([
        PengajuanApproved::class,
        PengajuanNomorGenerated::class,
        PengajuanStatusChanged::class,
        PengajuanSubmitted::class,
    ]);
    Storage::fake('public');

    $this->actingAs($guru)->post(route('pengajuan.store'), [
        'blanko_id' => $blanko->id,
        'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
        'nama_kegiatan' => 'Rapat Dinas',
        'jenis_surat' => 'Surat Tugas',
        'aksi' => 'submit',
    ])->assertRedirect();

    $pengajuan = PengajuanSurat::latest()->first();
    expect($pengajuan->status)->toBe(StatusPengajuan::DIAJUKAN)
        ->and($pengajuan->current_step)->toBe(1);

    $this->actingAs($admin)->post(route('approval.verify', $pengajuan), [
        'catatan' => 'Data Lengkap',
    ])->assertRedirect();

    $pengajuan->refresh();
    expect($pengajuan->status)->toBe(StatusPengajuan::DIPROSES)
        ->and($pengajuan->current_step)->toBe(2)
        ->and($pengajuan->reviewed_by)->toBe($admin->id);

    $this->actingAs($pimpinan)->post(route('approval.approve', $pengajuan), [
        'catatan' => 'Setujui',
    ])->assertRedirect();

    $pengajuan->refresh();
    expect($pengajuan->status)->toBe(StatusPengajuan::DISETUJUI)
        ->and($pengajuan->current_step)->toBe(3)
        ->and($pengajuan->nomor_surat)->not->toBeNull()
        ->and($pengajuan->qr_token)->not->toBeNull()
        ->and($pengajuan->reviewed_by)->toBe($pimpinan->id);

    expect(Storage::disk('public')->exists("qr/{$pengajuan->qr_token}.png"))->toBeTrue();

    $logs = ApprovalLog::where('pengajuan_id', $pengajuan->id)->orderBy('step')->get();
    expect($logs)->toHaveCount(2)
        ->and($logs[0]->step)->toBe(1)
        ->and($logs[0]->approver_id)->toBe($admin->id)
        ->and($logs[1]->step)->toBe(2)
        ->and($logs[1]->approver_id)->toBe($pimpinan->id);

    Event::assertDispatched(PengajuanSubmitted::class);
    Event::assertDispatched(PengajuanApproved::class, 2);
    Event::assertDispatched(PengajuanNomorGenerated::class);
    Event::assertDispatched(PengajuanStatusChanged::class, 5);
});

it('Guru Tidak Bisa Akses Pengajuan Guru Lain (IDOR Protection)', function () {
    $data = createFullApprovalFlowTestData();
    $guruB = User::factory()->create(['role_id' => Role::where('name', 'guru')->value('id')]);

    $pengajuan = PengajuanSurat::factory()->create([
        'user_id' => $guruB->id,
        'blanko_id' => $data['blanko']->id,
    ]);

    $this->actingAs($data['guru'])
        ->get(route('pengajuan.show', $pengajuan))
        ->assertForbidden();
});

it('Guru Tidak Bisa Submit Pengajuan Orang Lain', function () {
    $data = createFullApprovalFlowTestData();
    $guruB = User::factory()->create(['role_id' => Role::where('name', 'guru')->value('id')]);

    $pengajuan = PengajuanSurat::factory()->create([
        'user_id' => $guruB->id,
        'blanko_id' => $data['blanko']->id,
        'status' => StatusPengajuan::DRAFT,
    ]);

    $this->actingAs($data['guru'])
        ->post(route('pengajuan.submit', $pengajuan))
        ->assertForbidden();

    expect($pengajuan->fresh()->status)->toBe(StatusPengajuan::DRAFT);
});

it('Admin Tidak Bisa Approve Final Tanpa Pimpinan', function () {
    $data = createFullApprovalFlowTestData();
    $pengajuan = PengajuanSurat::factory()->diproses()->create([
        'user_id' => $data['guru']->id,
        'blanko_id' => $data['blanko']->id,
    ]);

    $this->actingAs($data['admin'])
        ->post(route('approval.approve', $pengajuan))
        ->assertForbidden();

    expect($pengajuan->fresh()->status)->toBe(StatusPengajuan::DIPROSES);
});

it('Event PengajuanSubmitted Ter-Dispatch Saat Submit', function () {
    $data = createFullApprovalFlowTestData();
    Event::fake([PengajuanStatusChanged::class, PengajuanSubmitted::class]);

    $this->actingAs($data['guru'])->post(route('pengajuan.store'), [
        'blanko_id' => $data['blanko']->id,
        'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
        'nama_kegiatan' => 'Rapat',
        'jenis_surat' => 'Surat Tugas',
        'aksi' => 'submit',
    ])->assertRedirect();

    Event::assertDispatched(PengajuanSubmitted::class);
});

it('Event PengajuanApproved Ter-Dispatch di Kedua Step', function () {
    $data = createFullApprovalFlowTestData();
    Event::fake([PengajuanApproved::class, PengajuanStatusChanged::class]);
    Storage::fake('public');

    $pengajuan = PengajuanSurat::factory()->diajukan()->create([
        'user_id' => $data['guru']->id,
        'blanko_id' => $data['blanko']->id,
    ]);

    $this->actingAs($data['admin'])
        ->post(route('approval.verify', $pengajuan))
        ->assertRedirect();
    Event::assertDispatched(PengajuanApproved::class, fn ($e) => $e->step === 1);

    $this->actingAs($data['pimpinan'])
        ->post(route('approval.approve', $pengajuan->fresh()))
        ->assertRedirect();
    Event::assertDispatched(PengajuanApproved::class, fn ($e) => $e->step === 2);
});
