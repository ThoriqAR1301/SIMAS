<?php

declare(strict_types=1);

use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    /** @var User $this->user */
    $this->user = User::factory()->create();

    Storage::fake('public');

    $this->validData = [
        'random_code' => 'SM-ABC123',
        'nama_surat' => 'Undangan Workshop',
        'kategori_folder' => 'Undangan',
        'asal_surat' => 'PT Contoh',
        'tanggal_diterima' => '2026-11-02',
        'deskripsi' => 'Undangan kegiatan industri',
    ];
});

describe('Surat Masuk Module', function () {

    describe('Access Control', function () {
        
        test('guests cannot access surat masuk pages', function () {
            $this->get(route('surat-masuk.index'))->assertRedirect(route('login'));
            $this->post(route('surat-masuk.store'), $this->validData)->assertRedirect(route('login'));
        });

        test('authenticated users can see the archive page', function () {
            $this->actingAs($this->user)
                ->get(route('surat-masuk.index'))
                ->assertOk()
                ->assertSee('Arsip Surat Masuk');
        });
    });

    describe('CRUD Operations', function () {
        
        test('can create a new archived incoming letter', function () {
            $this->actingAs($this->user)
                ->post(route('surat-masuk.store'), $this->validData)
                ->assertRedirect(route('surat-masuk.index'))
                ->assertSessionHas('success');

            $suratMasuk = SuratMasuk::firstOrFail();
            
            $this->assertDatabaseHas('surat_masuks', [
                'id' => $suratMasuk->id,
                'user_id' => $this->user->id,
                'random_code' => 'SM-ABC123',
                'kategori_folder' => 'Undangan',
            ]);
        });

        test('can show the detail of a surat masuk', function () {
            $suratMasuk = SuratMasuk::create($this->validData + ['user_id' => $this->user->id]);

            $this->actingAs($this->user)
                ->get(route('surat-masuk.show', $suratMasuk))
                ->assertOk()
                ->assertSee('Undangan Workshop');
        });

        test('can edit an existing surat masuk', function () {
            $suratMasuk = SuratMasuk::create($this->validData + ['user_id' => $this->user->id]);

            $this->actingAs($this->user)
                ->get(route('surat-masuk.edit', $suratMasuk))
                ->assertOk()
                ->assertSee('SM-ABC123');
        });

        test('can update an existing surat masuk', function () {
            $suratMasuk = SuratMasuk::create($this->validData + ['user_id' => $this->user->id]);

            $this->actingAs($this->user)
                ->put(route('surat-masuk.update', $suratMasuk), array_merge($this->validData, [
                    'nama_surat' => 'Undangan Workshop Nasional',
                ]))
                ->assertRedirect(route('surat-masuk.index'))
                ->assertSessionHas('success');

            $this->assertDatabaseHas('surat_masuks', [
                'id' => $suratMasuk->id,
                'nama_surat' => 'Undangan Workshop Nasional',
            ]);
        });

        test('can delete a surat masuk', function () {
            $suratMasuk = SuratMasuk::create($this->validData + ['user_id' => $this->user->id]);

            $this->actingAs($this->user)
                ->delete(route('surat-masuk.destroy', $suratMasuk))
                ->assertRedirect(route('surat-masuk.index'))
                ->assertSessionHas('success');

            $this->assertModelMissing($suratMasuk);
        });
    });

    describe('File Attachments', function () {
        
        test('can upload a file_surat during creation', function () {
            $file = UploadedFile::fake()->create('scan_surat.pdf', 1024); 

            $this->actingAs($this->user)
                ->post(route('surat-masuk.store'), array_merge($this->validData, [
                    'file_surat' => $file,
                ]));

            $suratMasuk = SuratMasuk::firstOrFail();
            
            expect($suratMasuk->file_surat)->not->toBeNull();
            
            expect(Storage::disk('public')->exists($suratMasuk->file_surat))->toBeTrue();
        });

        test('can replace an existing file_surat during update', function () {
            $oldFile = UploadedFile::fake()->create('old_scan.pdf', 1024);
            $oldPath = $oldFile->store('surat_masuk', 'public');
            
            $suratMasuk = SuratMasuk::create($this->validData + [
                'user_id' => $this->user->id, 
                'file_surat' => $oldPath
            ]);

            $newFile = UploadedFile::fake()->create('new_scan.pdf', 1024);

            $this->actingAs($this->user)
                ->put(route('surat-masuk.update', $suratMasuk), array_merge($this->validData, [
                    'file_surat' => $newFile,
                ]));

            $suratMasuk->refresh();

            expect(Storage::disk('public')->exists($oldPath))->toBeFalse();
            
            expect(Storage::disk('public')->exists($suratMasuk->file_surat))->toBeTrue();
        });
    });

    describe('Search and Filtering', function () {
        
        beforeEach(function () {
            SuratMasuk::create(array_merge($this->validData, [
                'user_id' => $this->user->id,
                'nama_surat' => 'Undangan Dinas',
                'kategori_folder' => 'Surat Dinas'
            ]));
            
            SuratMasuk::create(array_merge($this->validData, [
                'user_id' => $this->user->id,
                'nama_surat' => 'Penawaran Kerjasama',
                'kategori_folder' => 'Surat Industri'
            ]));
        });

        test('can search by nama_surat', function () {
            $this->actingAs($this->user)
                ->get(route('surat-masuk.index', ['search' => 'Penawaran']))
                ->assertOk()
                ->assertSee('Penawaran Kerjasama')
                ->assertDontSee('Undangan Dinas');
        });

        test('can filter by kategori_folder', function () {
            $this->actingAs($this->user)
                ->get(route('surat-masuk.index', ['folder' => 'Surat Dinas']))
                ->assertOk()
                ->assertSee('Undangan Dinas')
                ->assertDontSee('Penawaran Kerjasama');
        });
    });

    describe('Validation Rules', function () {
        
        test('rejects invalid file formats (exe)', function () {
            $file = UploadedFile::fake()->create('virus.exe', 100);

            $this->actingAs($this->user)
                ->post(route('surat-masuk.store'), array_merge($this->validData, [
                    'file_surat' => $file,
                ]))
                ->assertSessionHasErrors('file_surat');
        });

        test('rejects missing required fields', function () {
            $this->actingAs($this->user)
                ->post(route('surat-masuk.store'), [])
                ->assertSessionHasErrors(['nama_surat', 'kategori_folder', 'asal_surat', 'tanggal_diterima']);
        });
    });

});