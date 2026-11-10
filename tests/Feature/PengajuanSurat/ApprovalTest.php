<?php

declare(strict_types=1);

use App\Enums\StatusPengajuan;
use App\Events\PengajuanApproved;
use App\Events\PengajuanNomorGenerated;
use App\Events\PengajuanStatusChanged;
use App\Models\BlankoSurat;
use App\Models\LogAktivitas;
use App\Models\PengajuanSurat;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BlankoSuratSeeder;
use Database\Seeders\PengaturanSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Event;

class ApprovalTestCase extends Tests\TestCase
{
    public User $guru;

    public User $admin;

    public User $pimpinan;

    public PengajuanSurat $pengajuan;
}

uses(ApprovalTestCase::class);

beforeEach(function () {
    /** @var ApprovalTestCase $this */
    $this->seed([
        RoleSeeder::class,
        PermissionSeeder::class,
        RolePermissionSeeder::class,
        BlankoSuratSeeder::class,
        PengaturanSeeder::class,
    ]);

    $this->guru = User::factory()->create(['role_id' => Role::where('name', 'guru')->value('id')]);
    $this->admin = User::factory()->create(['role_id' => Role::where('name', 'admin_tu')->value('id')]);
    $this->pimpinan = User::factory()->create(['role_id' => Role::where('name', 'pimpinan')->value('id')]);

    $blanko = BlankoSurat::firstOrFail();
    $this->pengajuan = PengajuanSurat::factory()->create([
        'user_id' => $this->guru->id,
        'blanko_id' => $blanko->id,
        'status' => StatusPengajuan::DIAJUKAN,
        'current_step' => 1,
    ]);
});

describe('Approval Workflow', function () {

    describe('Access Control', function () {
        
        test('admin can see the approval queue', function () {
            $response = $this->actingAs($this->admin)->get(route('approval.index'));

            $response->assertOk()
                ->assertViewHas('pengajuan', fn ($p) => $p->contains('id', $this->pengajuan->id));
        });

        test('guru cannot access the approval page', function () {
            $this->actingAs($this->guru)
                ->get(route('approval.index'))
                ->assertForbidden();
        });

        test('pimpinan cannot perform admin verification', function () {
            $this->actingAs($this->pimpinan)
                ->post(route('approval.verify', $this->pengajuan))
                ->assertForbidden();
        });

    });

    describe('Admin TU Actions', function () {
        
        test('admin can verify a request and forward it to pimpinan', function () {
            $response = $this->actingAs($this->admin)
                ->post(route('approval.verify', $this->pengajuan), ['catatan' => 'Data lengkap']);

            $response->assertRedirect();

            $p = $this->pengajuan->fresh();
            expect($p->status)->toBe(StatusPengajuan::DIPROSES)
                ->and($p->current_step)->toBe(2);

            $this->assertDatabaseHas('approval_log', [
                'pengajuan_id' => $p->id,
                'step' => 1,
                'aksi' => 'approve',
                'approver_id'  => $this->admin->id,
            ]);

            expect(LogAktivitas::where('aksi', 'verify_pengajuan')->exists())->toBeTrue();
        });

        test('admin cannot approve directly without verification', function () {
            $this->actingAs($this->admin)
                ->post(route('approval.approve', $this->pengajuan))
                ->assertForbidden();
        });

        test('admin can reject a request at step 1', function () {
            $this->actingAs($this->admin)
                ->post(route('approval.reject', $this->pengajuan), ['catatan' => 'Berkas kurang lengkap']);

            $p = $this->pengajuan->fresh();
            expect($p->status)->toBe(StatusPengajuan::DITOLAK);

            expect(LogAktivitas::where('aksi', 'reject_pengajuan')->exists())->toBeTrue();
        });

    });

    describe('Pimpinan Actions', function () {
        
        beforeEach(function () {
            $this->actingAs($this->admin)
                ->post(route('approval.verify', $this->pengajuan), ['catatan' => 'OK']);
            $this->pengajuan->refresh();
        });

        test('pimpinan can approve and a letter number is generated', function () {
            Event::fake([
                PengajuanApproved::class,
                PengajuanNomorGenerated::class,
                PengajuanStatusChanged::class,
            ]);

            $response = $this->actingAs($this->pimpinan)
                ->post(route('approval.approve', $this->pengajuan), ['catatan' => 'Setujui.']);

            $response->assertRedirect();

            $p = $this->pengajuan->fresh();
            expect($p->status)->toBe(StatusPengajuan::DISETUJUI)
                ->and($p->current_step)->toBe(3)
                ->and($p->nomor_surat)->not->toBeNull()
                ->and($p->reviewed_by)->toBe($this->pimpinan->id);

            expect(LogAktivitas::where('aksi', 'approve_pengajuan')->exists())->toBeTrue();

            Event::assertDispatched(PengajuanApproved::class, 2);
            Event::assertDispatched(PengajuanNomorGenerated::class);
        });

        test('pimpinan can reject a verified request', function () {
            $this->actingAs($this->pimpinan)
                ->post(route('approval.reject', $this->pengajuan), ['catatan' => 'Jadwal bentrok']);

            $p = $this->pengajuan->fresh();
            expect($p->status)->toBe(StatusPengajuan::DITOLAK);

            expect(LogAktivitas::where('aksi', 'reject_pengajuan')->exists())->toBeTrue();
        });

        test('pimpinan can request a revision', function () {
            $this->actingAs($this->pimpinan)
                ->post(route('approval.revisi', $this->pengajuan), ['catatan' => 'Mohon ubah tanggal']);

            $p = $this->pengajuan->fresh();
            
            expect($p->status)->toBe(StatusPengajuan::DRAFT)
                ->and($p->current_step)->toBe(0);

            expect(LogAktivitas::where('aksi', 'revisi_pengajuan')->exists())->toBeTrue();
        });

    });

});