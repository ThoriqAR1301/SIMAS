<?php

use App\Models\BlankoSurat;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BlankoSuratSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @return array{guru: User, blanko: BlankoSurat}
 */
function createValidationTestData(): array
{
    (new RoleSeeder)->run();
    (new PermissionSeeder)->run();
    (new RolePermissionSeeder)->run();
    (new BlankoSuratSeeder)->run();

    return [
        'guru' => User::factory()->create([
            'role_id' => Role::where('name', 'guru')->value('id'),
        ]),
        'blanko' => BlankoSurat::query()->firstOrFail(),
    ];
}

it('Rejects Past Performance Dates With The Expected Message', function () {
    $data = createValidationTestData();

    $this->actingAs($data['guru'])->post(route('pengajuan.store'), [
        'blanko_id' => $data['blanko']->id,
        'tanggal_pelaksanaan' => now()->subDay()->format('Y-m-d'),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
    ])->assertSessionHasErrors([
        'tanggal_pelaksanaan' => 'Tanggal Pelaksanaan Tidak Boleh Di Masa Lalu',
    ]);
});

it('Rejects An End Time Before The Start Time With The Expected Message', function () {
    $data = createValidationTestData();

    $this->actingAs($data['guru'])->post(route('pengajuan.store'), [
        'blanko_id' => $data['blanko']->id,
        'tanggal_pelaksanaan' => now()->addDay()->format('Y-m-d'),
        'waktu_mulai' => '14:00',
        'waktu_selesai' => '10:00',
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
    ])->assertSessionHasErrors([
        'waktu_selesai' => 'Waktu Selesai Harus Setelah Waktu Mulai',
    ]);
});

it('Rejects Attachments Larger Than Five Megabytes With The Expected Message', function () {
    $data = createValidationTestData();
    Storage::fake('public');

    $response = $this->actingAs($data['guru'])->post(route('pengajuan.store'), [
        'blanko_id' => $data['blanko']->id,
        'tanggal_pelaksanaan' => now()->addDay()->format('Y-m-d'),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
        'lampiran' => [UploadedFile::fake()->create('large.pdf', 6000)],
    ]);

    $response->assertSessionHasErrors('lampiran.0');
    $this->assertDatabaseCount('pengajuan_surat', 0);
    expect(Storage::disk('public')->allFiles())->toBe([]);
});

it('Rejects Executable Attachments With The Expected Message', function () {
    $data = createValidationTestData();
    Storage::fake('public');

    $this->actingAs($data['guru'])->post(route('pengajuan.store'), [
        'blanko_id' => $data['blanko']->id,
        'tanggal_pelaksanaan' => now()->addDay()->format('Y-m-d'),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
        'lampiran' => [UploadedFile::fake()->create('virus.exe', 100)],
    ])->assertSessionHasErrors([
        'lampiran.0' => 'Lampiran Harus PDF, JPG, Atau PNG',
    ]);
});

it('Rejects A Blanko Id That Does Not Exist With The Expected Message', function () {
    $data = createValidationTestData();

    $this->actingAs($data['guru'])->post(route('pengajuan.store'), [
        'blanko_id' => 99999,
        'tanggal_pelaksanaan' => now()->addDay()->format('Y-m-d'),
        'nama_kegiatan' => 'Test',
        'jenis_surat' => 'Test',
    ])->assertSessionHasErrors([
        'blanko_id' => 'Blanko Surat Tidak Valid',
    ]);
});
