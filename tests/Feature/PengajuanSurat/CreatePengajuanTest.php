<?php

declare(strict_types=1);

use App\Enums\StatusPengajuan;
use App\Models\BlankoSurat;
use App\Models\LogAktivitas;
use App\Models\PengajuanSurat;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BlankoSuratSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function createPengajuanTestGuru(): User
{
    return User::findOrFail(app('pengajuan_test.guru_id'));
}

function createPengajuanTestAdmin(): User
{
    return User::findOrFail(app('pengajuan_test.admin_id'));
}

function createPengajuanTestBlanko(): BlankoSurat
{
    return BlankoSurat::findOrFail(app('pengajuan_test.blanko_id'));
}

function createPengajuanTestData(): array
{
    return app('pengajuan_test.valid_data');
}

beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
        PermissionSeeder::class,
        RolePermissionSeeder::class,
        BlankoSuratSeeder::class,
    ]);

    $guru = User::factory()->create([
        'role_id' => Role::where('name', 'guru')->value('id'),
    ]);
    
    $admin = User::factory()->create([
        'role_id' => Role::where('name', 'admin_tu')->value('id'),
    ]);

    $blanko = BlankoSurat::firstOrFail();

    app()->instance('pengajuan_test.guru_id', $guru->id);
    app()->instance('pengajuan_test.admin_id', $admin->id);
    app()->instance('pengajuan_test.blanko_id', $blanko->id);
    app()->instance('pengajuan_test.valid_data', [
        'blanko_id' => $blanko->id,
        'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
        'nama_kegiatan' => 'Rapat Dinas',
        'jenis_surat' => 'Surat Tugas',
        'aksi' => 'draft',
    ]);
});

describe('Pengajuan Creation', function () {

    describe('Access Control', function () {
        
        test('guru can access the create form', function () {
            $this->actingAs(createPengajuanTestGuru())
                ->get(route('pengajuan.create'))
                ->assertOk();
        });

        test('guest cannot create a pengajuan', function () {
            $this->post(route('pengajuan.store'), createPengajuanTestData())
                ->assertRedirect(route('login'));
        });

        test('user without permission is forbidden', function () {
            $this->actingAs(createPengajuanTestAdmin())
                ->post(route('pengajuan.store'), createPengajuanTestData())
                ->assertForbidden();
        });
    });

    describe('Core Creation', function () {
        
        test('guru can save a new pengajuan as draft', function () {
            $response = $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), createPengajuanTestData());

            $response->assertRedirect();
            
            $pengajuan = PengajuanSurat::latest()->first();
            
            expect($pengajuan->status)->toBe(StatusPengajuan::DRAFT)
                ->and($pengajuan->current_step)->toBe(0)
                ->and($pengajuan->user_id)->toBe(createPengajuanTestGuru()->id);

            $this->assertDatabaseHas('pengajuan_surat', [
                'user_id' => createPengajuanTestGuru()->id,
                'nama_kegiatan' => 'Rapat Dinas',
            ]);

            expect(LogAktivitas::where('aksi', 'create_pengajuan')
                ->where('model_id', $pengajuan->id)
                ->exists())->toBeTrue();
        });

        test('guru can submit directly when creating', function () {
            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), array_merge(createPengajuanTestData(), ['aksi' => 'submit']));

            $pengajuan = PengajuanSurat::latest()->first();
            
            expect($pengajuan->status)->toBe(StatusPengajuan::DIAJUKAN)
                ->and($pengajuan->current_step)->toBe(1);
        });

        test('nomor pengajuan is generated automatically', function () {
            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), createPengajuanTestData());

            $pengajuan = PengajuanSurat::latest()->first();
            
            expect($pengajuan->nomor_pengajuan)->toMatch('/^PGJ\/\d{4}\/\d{2}\/\d{4}$/');
        });
    });

    describe('Validation Rules', function () {
        
        test('fails if tanggal_pelaksanaan is in the past', function () {
            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), array_merge(createPengajuanTestData(), [
                    'tanggal_pelaksanaan' => now()->subDay()->format('Y-m-d'),
                ]))
                ->assertSessionHasErrors('tanggal_pelaksanaan');
        });

        test('fails if nama_kegiatan exceeds 200 characters', function () {
            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), array_merge(createPengajuanTestData(), [
                    'nama_kegiatan' => str_repeat('A', 201),
                ]))
                ->assertSessionHasErrors('nama_kegiatan');
        });

        test('fails if waktu_selesai is before waktu_mulai', function () {
            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), array_merge(createPengajuanTestData(), [
                    'waktu_mulai'   => '14:00',
                    'waktu_selesai' => '10:00',
                ]))
                ->assertSessionHasErrors('waktu_selesai');
        });

        test('fails if blanko is inactive', function () {
            createPengajuanTestBlanko()->update(['is_active' => false]);
            
            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), createPengajuanTestData())
                ->assertSessionHasErrors('blanko_id');
        });
    });

    describe('Panitia Array', function () {
        
        test('saves panitia data correctly into JSON', function () {
            $panitiaData = [
                ['nama' => 'Ayu', 'jabatan' => 'Ketua', 'keterangan' => 'Koordinasi'],
                ['nama' => 'Budi', 'jabatan' => 'Anggota', 'keterangan' => null],
            ];

            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), array_merge(createPengajuanTestData(), [
                    'panitia' => $panitiaData,
                ]));

            $pengajuan = PengajuanSurat::latest()->first();
            expect($pengajuan->panitia)->toBe($panitiaData);
        });

        test('filters out empty panitia rows (Premium Feature)', function () {
            $panitiaData = [
                ['nama' => 'Ayu', 'jabatan' => 'Ketua', 'keterangan' => null],
                ['nama' => '', 'jabatan' => '', 'keterangan' => ''], 
            ];

            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), array_merge(createPengajuanTestData(), [
                    'panitia' => $panitiaData,
                ]));

            $pengajuan = PengajuanSurat::latest()->first();
            
            expect($pengajuan->panitia)->toHaveCount(1);
            expect($pengajuan->panitia[0]['nama'])->toBe('Ayu');
        });
    });

    describe('File Uploads', function () {
        
        beforeEach(function () {
            Storage::fake('public');
        });

        test('saves lampiran when uploaded', function () {
            $file = UploadedFile::fake()->create('dokumen.pdf', 1024); 

            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), array_merge(createPengajuanTestData(), [
                    'lampiran' => [$file],
                ]));

            $pengajuan = PengajuanSurat::latest()->first();
            
            expect($pengajuan->lampiran)->toHaveCount(1);
            
            expect(Storage::disk('public')->exists($pengajuan->lampiran[0]->path))->toBeTrue();
        });

        test('fails if file size exceeds 5 MB', function () {
            $file = UploadedFile::fake()->create('besar.pdf', 6000); 

            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), array_merge(createPengajuanTestData(), [
                    'lampiran' => [$file],
                ]))
                ->assertSessionHasErrors('lampiran.0');
        });

        test('fails if file format is not pdf/jpg/png', function () {
            $file = UploadedFile::fake()->create('virus.exe', 100);

            $this->actingAs(createPengajuanTestGuru())
                ->post(route('pengajuan.store'), array_merge(createPengajuanTestData(), [
                    'lampiran' => [$file],
                ]))
                ->assertSessionHasErrors('lampiran.0');
        });
    });

});