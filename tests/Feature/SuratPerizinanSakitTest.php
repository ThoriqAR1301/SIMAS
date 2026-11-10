<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\SuratPerizinanSakit;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SuratPerizinanSakitTestCase extends Tests\TestCase
{
    public User $guru;

    public User $adminTu;

    public User $pimpinan;

    public Closure $createSurat;
}

uses(SuratPerizinanSakitTestCase::class);

beforeEach(function () {
    /** @var SuratPerizinanSakitTestCase $this */
    $this->seed(RoleSeeder::class);
    
    $guruRole = Role::where('name', 'guru')->firstOrFail();
    $adminRole = Role::where('name', 'admin_tu')->firstOrFail();
    $pimpinanRole = Role::where('name', 'pimpinan')->firstOrFail();

    $this->guru = User::factory()->create(['role_id' => $guruRole->id]);
    $this->adminTu = User::factory()->create(['role_id' => $adminRole->id]);
    $this->pimpinan = User::factory()->create(['role_id' => $pimpinanRole->id]);

    Storage::fake('local');

    $this->createSurat = function (User $user, array $attributes = []) {
        $surat = new SuratPerizinanSakit([
            'tanggal_mulai' => $attributes['tanggal_mulai'] ?? '2026-11-03',
            'tanggal_selesai' => $attributes['tanggal_selesai'] ?? '2026-11-04',
            'alasan' => $attributes['alasan'] ?? 'Memerlukan waktu istirahat.',
        ]);
        
        $surat->user()->associate($user);
        $surat->surat_dokter_path = $attributes['surat_dokter_path'] ?? 'surat-dokter/surat.pdf';
        $surat->status = $attributes['status'] ?? SuratPerizinanSakit::STATUS_MENUNGGU;
        $surat->catatan_tu = $attributes['catatan_tu'] ?? null;
        $surat->save();

        return $surat;
    };
});

describe('Surat Perizinan Sakit', function () {

    describe('Access Control & Routing', function () {
        
        test('redirects guests to login from the sick leave requests', function () {
            $this->get(route('surat-perizinan-sakit.index'))
                ->assertRedirect(route('login'));
        });

        test('renders proper pages for teacher and admin', function () {
            $surat = ($this->createSurat)($this->guru);

            $this->actingAs($this->guru)->get(route('surat-perizinan-sakit.create'))
                ->assertOk()->assertSee('Formulir Pengajuan');

            $this->get(route('surat-perizinan-sakit.index'))
                ->assertOk()->assertSee('Daftar Pengajuan Anda');

            $this->get(route('surat-perizinan-sakit.show', $surat))
                ->assertOk();

            $this->actingAs($this->adminTu)->get(route('surat-perizinan-sakit.index'))
                ->assertOk()->assertSee($this->guru->name);

            $this->get(route('surat-perizinan-sakit.show', $surat))
                ->assertOk()->assertSee('Pemeriksaan Detail Pengajuan');
        });

        test('shows correct dashboard shortcuts for each role', function () {
            $this->actingAs($this->guru)->get(route('dashboard'))
                ->assertOk()->assertSee(route('surat-perizinan-sakit.create'));

            $this->actingAs($this->adminTu)->get(route('dashboard'))
                ->assertOk()->assertSee(route('surat-perizinan-sakit.index'));
        });
    });

    describe('Creation & File Uploads', function () {
        
        test('lets a teacher submit a sick leave request with a private document', function () {
            $response = $this->actingAs($this->guru)->post(route('surat-perizinan-sakit.store'), [
                'tanggal_mulai' => '2026-11-03',
                'tanggal_selesai' => '2026-11-04',
                'alasan' => 'Memerlukan waktu istirahat',
                'surat_dokter' => UploadedFile::fake()->create('surat-dokter.pdf', 100, 'application/pdf'),
            ]);

            $surat = SuratPerizinanSakit::firstOrFail();

            $response->assertRedirect(route('surat-perizinan-sakit.create'))
                ->assertSessionHas('success');
                
            $this->assertDatabaseHas('surat_perizinan_sakit', [
                'id' => $surat->id,
                'user_id' => $this->guru->id,
                'status' => SuratPerizinanSakit::STATUS_MENUNGGU,
            ]);

            $this->assertTrue(Storage::disk('local')->exists($surat->surat_dokter_path));

            $this->get(route('surat-perizinan-sakit.dokter', $surat))
                ->assertOk()->assertHeader('content-type', 'application/pdf');
        });

        test('rejects unsupported medical document types', function () {
            $this->actingAs($this->guru)
                ->post(route('surat-perizinan-sakit.store'), [
                    'tanggal_mulai' => '2026-11-03',
                    'tanggal_selesai' => '2026-11-04',
                    'alasan' => 'Memerlukan waktu istirahat',
                    'surat_dokter' => UploadedFile::fake()->create('catatan.exe', 100, 'application/octet-stream'),
                ])
                ->assertSessionHasErrors('surat_dokter');

            $this->assertDatabaseCount('surat_perizinan_sakit', 0);
        });
    });

    describe('Data Security (IDOR)', function () {
        
        test('forbids teachers from viewing another users request or document', function () {
            $otherTeacher = User::factory()->create(['role_id' => $this->guru->role_id]);
            $surat = ($this->createSurat)($otherTeacher);

            $this->actingAs($this->guru)
                ->get(route('surat-perizinan-sakit.show', $surat))
                ->assertNotFound();

            $this->get(route('surat-perizinan-sakit.dokter', $surat))
                ->assertNotFound();
        });
    });

    describe('Admin Review Workflow', function () {
        
        test('lets only an admin tu decide a pending request and records the note', function () {
            $surat = ($this->createSurat)($this->guru);

            $this->actingAs($this->pimpinan)
                ->post(route('surat-perizinan-sakit.verify', $surat), [
                    'keputusan' => SuratPerizinanSakit::STATUS_DISETUJUI,
                ])
                ->assertForbidden();

            $this->actingAs($this->adminTu)
                ->post(route('surat-perizinan-sakit.verify', $surat), [
                    'keputusan' => SuratPerizinanSakit::STATUS_DITOLAK,
                    'catatan_tu' => 'Mohon lampirkan dokumen yang terbaca',
                ])
                ->assertRedirect(route('surat-perizinan-sakit.show', $surat));

            $this->assertDatabaseHas('surat_perizinan_sakit', [
                'id' => $surat->id,
                'status' => SuratPerizinanSakit::STATUS_DITOLAK,
                'catatan_tu' => 'Mohon lampirkan dokumen yang terbaca',
            ]);
        });

        test('prevents a processed request from being decided again', function () {
            $surat = ($this->createSurat)($this->guru, [
                'status' => SuratPerizinanSakit::STATUS_DISETUJUI,
            ]);

            $this->actingAs($this->adminTu)
                ->post(route('surat-perizinan-sakit.verify', $surat), [
                    'keputusan' => SuratPerizinanSakit::STATUS_DITOLAK,
                    'catatan_tu' => 'Catatan baru',
                ])
                ->assertForbidden();

            $this->assertDatabaseHas('surat_perizinan_sakit', [
                'id' => $surat->id,
                'status' => SuratPerizinanSakit::STATUS_DISETUJUI,
                'catatan_tu' => null,
            ]);
        });
    });

    describe('Revision Workflow', function () {
        
        test('returns a revised request to the review queue and clears the old note', function () {
            $surat = ($this->createSurat)($this->guru, [
                'status' => SuratPerizinanSakit::STATUS_PERLU_REVISI,
                'catatan_tu' => 'Mohon perjelas tanggal izin',
            ]);

            $this->actingAs($this->guru)
                ->post(route('surat-perizinan-sakit.revisi', $surat), [
                    'tanggal_mulai' => '2026-11-05',
                    'tanggal_selesai' => '2026-11-06',
                    'alasan' => 'Informasi sakit telah diperjelas',
                ])
                ->assertRedirect(route('surat-perizinan-sakit.show', $surat));

            $this->assertDatabaseHas('surat_perizinan_sakit', [
                'id' => $surat->id,
                'status' => SuratPerizinanSakit::STATUS_MENUNGGU,
                'catatan_tu' => null,
                'alasan' => 'Informasi sakit telah diperjelas',
            ]);
        });
    });

});