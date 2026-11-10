<?php

declare(strict_types=1);

use App\Models\IzinNotifikasi;
use App\Models\Role;
use App\Models\SuratIzin;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed(RoleSeeder::class);

    /** @var Role $guruRole */
    $this->{'guruRole'} = Role::where('name', 'guru')->firstOrFail();
    /** @var Role $adminRole */
    $this->{'adminRole'} = Role::where('name', 'admin_tu')->firstOrFail();
    /** @var Role $pimpinanRole */
    $this->{'pimpinanRole'} = Role::where('name', 'pimpinan')->firstOrFail();

    /** @var User $guru */
    $this->{'guru'} = User::factory()->create(['role_id' => $this->guruRole->id]);
    /** @var User $adminTu */
    $this->{'adminTu'} = User::factory()->create(['role_id' => $this->adminRole->id]);
    /** @var User $pimpinan */
    $this->{'pimpinan'} = User::factory()->create(['role_id' => $this->pimpinanRole->id]);

    /** @var callable(User $user, array $attributes = []): SuratIzin $createSurat */
    $this->{'createSurat'} = function (User $user, array $attributes = []) {
        return SuratIzin::create($attributes + [
            'user_id' => $user->id,
            'nama' => $user->name,
            'jabatan' => 'Guru',
            'tanggal_surat' => '2026-11-02',
            'tanggal_izin' => '2026-11-03',
            'alasan' => 'Keperluan keluarga',
            'status' => 'pending',
        ]);
    };
});

describe('Surat Izin Module', function () {

    describe('Access Control & Routing', function () {
        
        test('redirects guests to login from the leave-request archive', function () {
            $this->get(route('surat-izin.index'))->assertRedirect(route('login'));
        });

        test('shows the leave-request shortcut for teachers on the dashboard', function () {
            $this->actingAs($this->guru)
                ->get(route('dashboard'))
                ->assertOk()
                ->assertSee(route('surat-izin.index'));
        });

        test('shows the admin leave-request queue shortcut on the dashboard', function () {
            $this->actingAs($this->adminTu)
                ->get(route('dashboard'))
                ->assertOk()
                ->assertSee(route('admin.surat-izin.index'));
        });

        test('forbids teachers from accessing the admin leave-request queue', function () {
            $this->actingAs($this->guru)
                ->get(route('admin.surat-izin.index'))
                ->assertForbidden();
        });
    });

    describe('Create & Validation', function () {
        
        test('creates a pending leave request and notifies both reviewers', function () {
            $response = $this->actingAs($this->guru)->post(route('surat-izin.store'), [
                'tanggal_izin' => '2026-11-03',
                'alasan' => 'Keperluan keluarga',
                'tanda_tangan' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jf5sAAAAASUVORK5CYII=',
            ]);

            $surat = SuratIzin::firstOrFail();

            $response->assertRedirect(route('surat-izin.show', $surat));
            
            $this->assertDatabaseHas('surat_izins', [
                'id' => $surat->id,
                'user_id' => $this->guru->id,
                'nama' => $this->guru->name,
                'status' => 'pending',
            ]);
            
            $this->assertDatabaseHas('izin_notifikasis', [
                'user_id' => $this->adminTu->id,
                'surat_izin_id' => $surat->id,
                'judul' => 'Surat izin baru',
            ]);
            $this->assertDatabaseHas('izin_notifikasis', [
                'user_id' => $this->pimpinan->id,
                'surat_izin_id' => $surat->id,
                'judul' => 'Surat izin baru',
            ]);
        });

        test('rejects signature data that is not a PNG data URI', function () {
            $this->actingAs($this->guru)
                ->post(route('surat-izin.store'), [
                    'tanggal_izin' => '2026-11-03',
                    'alasan' => 'Keperluan keluarga',
                    'tanda_tangan' => 'https://example.test/signature.png',
                ])
                ->assertSessionHasErrors('tanda_tangan');

            $this->assertDatabaseCount('surat_izins', 0);
        });
    });

    describe('Update & View Security', function () {
        
        test('lets the owner update a pending leave request without replacing its signature', function () {
            $surat = ($this->createSurat)($this->guru, [
                'tanda_tangan' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jf5sAAAAASUVORK5CYII=',
            ]);

            $this->actingAs($this->guru)
                ->put(route('surat-izin.update', $surat), [
                    'tanggal_izin' => '2026-11-04',
                    'alasan' => 'Keperluan keluarga yang diperbarui',
                ])
                ->assertRedirect(route('surat-izin.show', $surat));

            $this->assertDatabaseHas('surat_izins', [
                'id' => $surat->id,
                'nama' => $this->guru->name,
                'jabatan' => 'Guru',
                'alasan' => 'Keperluan keluarga yang diperbarui',
                'tanda_tangan' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jf5sAAAAASUVORK5CYII=',
            ]);
            expect($surat->fresh()->tanggal_izin->toDateString())->toBe('2026-11-04');
        });

        test('forbids owners from editing a processed leave request', function () {
            $surat = ($this->createSurat)($this->guru, ['status' => 'disetujui']);

            $this->actingAs($this->guru)
                ->get(route('surat-izin.edit', $surat))
                ->assertForbidden();
        });

        test('forbids teachers from viewing another user leave request', function () {
            $otherTeacher = User::factory()->create(['role_id' => $this->guruRole->id]);
            $surat = ($this->createSurat)($otherTeacher);

            $this->actingAs($this->guru)
                ->get(route('surat-izin.show', $surat))
                ->assertForbidden();

            $this->get(route('surat-izin.pdf', $surat))
                ->assertForbidden();
        });

        test('renders a PDF letter for its owner', function () {
            $surat = ($this->createSurat)($this->guru);

            $this->actingAs($this->guru)
                ->get(route('surat-izin.pdf', $surat))
                ->assertOk()
                ->assertHeader('content-type', 'application/pdf');
        });
    });

    describe('Admin Approval Workflow', function () {
        
        test('lets an admin approve a pending request and notifies its owner', function () {
            $surat = ($this->createSurat)($this->guru);

            $this->actingAs($this->adminTu)
                ->get(route('admin.surat-izin.index'))
                ->assertOk()
                ->assertSee('Kelola Surat Izin')
                ->assertSee('<title>Kelola Surat Izin — SIMAS</title>', false);

            $this->actingAs($this->adminTu)
                ->post(route('admin.surat-izin.setujui', $surat), [
                    'catatan_admin' => 'Silakan koordinasikan pekerjaan',
                ])
                ->assertRedirect(route('admin.surat-izin.show', $surat));

            $this->assertDatabaseHas('surat_izins', [
                'id' => $surat->id,
                'status' => 'disetujui',
                'catatan_admin' => 'Silakan koordinasikan pekerjaan',
                'diproses_oleh' => $this->adminTu->id,
            ]);
            
            $this->assertDatabaseHas('izin_notifikasis', [
                'user_id' => $this->guru->id,
                'surat_izin_id' => $surat->id,
                'judul' => 'Surat izin disetujui',
            ]);
        });

        test('lets an admin reject a pending request and notifies its owner', function () {
            $surat = ($this->createSurat)($this->guru);

            $this->actingAs($this->adminTu)
                ->post(route('admin.surat-izin.tolak', $surat), [
                    'catatan_admin' => 'Mohon lampirkan dokumen pendukung',
                ])
                ->assertRedirect(route('admin.surat-izin.show', $surat));

            $this->assertDatabaseHas('surat_izins', [
                'id' => $surat->id,
                'status' => 'ditolak',
                'catatan_admin' => 'Mohon lampirkan dokumen pendukung',
            ]);
            
            $this->assertDatabaseHas('izin_notifikasis', [
                'user_id' => $this->guru->id,
                'surat_izin_id' => $surat->id,
                'judul' => 'Surat izin ditolak',
            ]);
        });

        test('rejects oversized approval notes without changing the request', function () {
            $surat = ($this->createSurat)($this->guru);

            $this->actingAs($this->adminTu)
                ->post(route('admin.surat-izin.setujui', $surat), [
                    'catatan_admin' => str_repeat('a', 501), 
                ])
                ->assertSessionHasErrors('catatan_admin');

            $this->assertDatabaseHas('surat_izins', [
                'id' => $surat->id,
                'status' => 'pending', 
            ]);
        });
    });

    describe('Admin Account Management', function () {
        
        test('lets only admin tu manage accounts and keeps stored passwords hashed', function () {
            $this->actingAs($this->pimpinan)
                ->get(route('admin.akun.index'))
                ->assertForbidden();

            $this->actingAs($this->adminTu)
                ->get(route('admin.akun.index'))
                ->assertOk()
                ->assertSee('Akun Guru & Pegawai');

            $this->get(route('admin.akun.create'))
                ->assertOk()
                ->assertSee('type="password" name="password"', false);

            $this->post(route('admin.akun.store'), [
                'name' => 'Guru Baru',
                'email' => 'guru.baru@example.test',
                'nip' => '198701012010011001',
                'jabatan' => 'Guru Matematika',
                'role_id' => $this->guruRole->id,
                'password' => 'KataSandi-123',
            ])->assertRedirect(route('admin.akun.index'));

            $this->assertDatabaseHas('users', [
                'name' => 'Guru Baru',
                'email' => 'guru.baru@example.test',
                'nip' => '198701012010011001',
                'jabatan' => 'Guru Matematika',
                'role_id' => $this->guruRole->id,
            ]);

            $account = User::where('email', 'guru.baru@example.test')->firstOrFail();
            expect(Hash::check('KataSandi-123', $account->password))->toBeTrue();

            $this->put(route('admin.akun.update', $account), [
                'name' => 'Guru Diperbarui',
                'email' => 'guru.baru@example.test',
                'nip' => '198701012010011001',
                'jabatan' => 'Wali Kelas',
                'role_id' => $this->guruRole->id,
                'password' => '',
            ])->assertRedirect(route('admin.akun.index'));

            $this->assertDatabaseHas('users', [
                'id' => $account->id,
                'name' => 'Guru Diperbarui',
                'jabatan' => 'Wali Kelas',
            ]);
            expect(Hash::check('KataSandi-123', $account->fresh()->password))->toBeTrue();

            $this->delete(route('admin.akun.destroy', $this->adminTu))
                ->assertRedirect()
                ->assertSessionHas('error');
            $this->assertModelExists($this->adminTu);

            $this->from(route('admin.akun.index'))
                ->delete(route('admin.akun.destroy', $account))
                ->assertRedirect(route('admin.akun.index'));
            $this->assertModelMissing($account);
        });
    });

    describe('Notifications', function () {
        
        test('returns only the signed-in user unread leave notification count', function () {
            $otherUser = User::factory()->create(['role_id' => $this->guruRole->id]);
            $surat = ($this->createSurat)($this->guru);

            $notification = IzinNotifikasi::create([
                'user_id' => $this->guru->id,
                'surat_izin_id' => $surat->id,
                'judul' => 'Baru',
                'pesan' => 'Pesan untuk pemilik',
            ]);
            
            IzinNotifikasi::create([
                'user_id' => $otherUser->id,
                'surat_izin_id' => $surat->id,
                'judul' => 'Baru',
                'pesan' => 'Pesan untuk pengguna lain',
            ]);

            $this->actingAs($this->guru)
                ->getJson(route('izin-notif.jumlah'))
                ->assertOk()
                ->assertExactJson(['count' => 1]);

            $this->post(route('izin-notif.baca', $notification))
                ->assertRedirect(route('surat-izin.show', $surat));

            expect($notification->fresh()->dibaca_at)->not->toBeNull();
        });
    });

});