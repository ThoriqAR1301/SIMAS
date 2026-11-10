<?php

declare(strict_types=1);

use App\Models\BlankoSurat;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BlankoSuratSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$guru = null;
$blanko = null;
$validData = [];

beforeEach(function () use (&$guru, &$blanko, &$validData) {
    $this->seed([
        RoleSeeder::class,
        PermissionSeeder::class,
        RolePermissionSeeder::class,
        BlankoSuratSeeder::class,
    ]);

    $guru = User::factory()->create([
        'role_id' => Role::where('name', 'guru')->value('id'),
    ]);

    $blanko = BlankoSurat::firstOrFail();

    Storage::fake('public');

    $validData = [
        'blanko_id' => $blanko->id,
        'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
        'nama_kegiatan' => 'Rapat Dinas',
        'jenis_surat' => 'Surat Tugas',
        'aksi' => 'draft',
    ];
});

describe('Pengajuan Validation Rules', function () {

    describe('Date & Time Constraints', function () {
        
        test('rejects past performance dates', function () {
            $this->actingAs($this->guru)->post(route('pengajuan.store'), array_merge($this->validData, [
                'tanggal_pelaksanaan' => now()->subDay()->format('Y-m-d'),
            ]))->assertSessionHasErrors([
                'tanggal_pelaksanaan' => 'Tanggal Pelaksanaan tidak boleh di masa lalu',
            ]);

            $this->assertDatabaseCount('pengajuan_surat', 0);
        });

        test('rejects an end time before the start time', function () {
            $this->actingAs($this->guru)->post(route('pengajuan.store'), array_merge($this->validData, [
                'waktu_mulai' => '14:00',
                'waktu_selesai' => '10:00',
            ]))->assertSessionHasErrors([
                'waktu_selesai' => 'Waktu Selesai harus setelah Waktu Mulai',
            ]);

            $this->assertDatabaseCount('pengajuan_surat', 0);
        });
    });

    describe('File Attachment Constraints', function () {
        
        test('rejects attachments larger than 5MB', function () {
            $largeFile = UploadedFile::fake()->create('large.pdf', 6000); 

            $this->actingAs($this->guru)->post(route('pengajuan.store'), array_merge($this->validData, [
                'lampiran' => [$largeFile],
            ]))->assertSessionHasErrors([
                'lampiran.0' => 'Ukuran lampiran maksimal 5 MB',
            ]);

            expect(Storage::disk('public')->allFiles())->toBeEmpty();
            $this->assertDatabaseCount('pengajuan_surat', 0);
        });

        test('rejects executable files (.exe)', function () {
            $virusFile = UploadedFile::fake()->create('virus.exe', 100);

            $this->actingAs($this->guru)->post(route('pengajuan.store'), array_merge($this->validData, [
                'lampiran' => [$virusFile],
            ]))->assertSessionHasErrors([
                'lampiran.0' => 'Lampiran harus berformat PDF, JPG, atau PNG',
            ]);

            expect(Storage::disk('public')->allFiles())->toBeEmpty();
            $this->assertDatabaseCount('pengajuan_surat', 0);
        });

        test('rejects more than five attachments', function () {
            $attachments = array_map(
                fn (int $index) => UploadedFile::fake()->create("file{$index}.pdf", 100),
                range(1, 6),
            );

            $this->actingAs($this->guru)->post(route('pengajuan.store'), array_merge($this->validData, [
                'lampiran' => $attachments,
            ]))->assertSessionHasErrors([
                'lampiran' => 'Maksimal 5 lampiran yang diperbolehkan',
            ]);

            expect(Storage::disk('public')->allFiles())->toBeEmpty();
            $this->assertDatabaseCount('pengajuan_surat', 0);
        });
    });

    describe('Relational & Text Constraints', function () {
        
        test('rejects a blanko id that does not exist', function () {
            $this->actingAs($this->guru)->post(route('pengajuan.store'), array_merge($this->validData, [
                'blanko_id' => 99999,
            ]))->assertSessionHasErrors([
                'blanko_id' => 'Blanko Surat tidak valid atau sudah tidak aktif',
            ]);
        });

        test('rejects an inactive blanko', function () {
            $this->blanko->update(['is_active' => false]);

            $this->actingAs($this->guru)->post(route('pengajuan.store'), $this->validData)
                ->assertSessionHasErrors('blanko_id');
        });

        test('rejects a nama_kegiatan exceeding 200 characters', function () {
            $this->actingAs($this->guru)->post(route('pengajuan.store'), array_merge($this->validData, [
                'nama_kegiatan' => str_repeat('A', 201),
            ]))->assertSessionHasErrors('nama_kegiatan');
        });

        test('rejects missing required fields', function () {
            $this->actingAs($this->guru)->post(route('pengajuan.store'), [])
                ->assertSessionHasErrors([
                    'blanko_id',
                    'tanggal_pelaksanaan',
                    'nama_kegiatan',
                    'jenis_surat',
                ]);
        });
    });

});