<?php

declare(strict_types=1);

use App\Enums\StatusPengajuan;
use App\Events\PengajuanActivityChanged;
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

beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
        PermissionSeeder::class,
        RolePermissionSeeder::class,
        BlankoSuratSeeder::class,
        PengaturanSeeder::class,
    ]);

    $this->{'guru'} = User::factory()->create(['role_id' => Role::where('name', 'guru')->value('id')]);
    $this->{'admin'} = User::factory()->create(['role_id' => Role::where('name', 'admin_tu')->value('id')]);
    $this->{'pimpinan'} = User::factory()->create(['role_id' => Role::where('name', 'pimpinan')->value('id')]);
    $this->{'blanko'} = BlankoSurat::firstOrFail();

    Storage::fake('public');
});

describe('Full Approval Flow', function () {

    test('Guru Submit → Admin Verify → Pimpinan Approve', function () {
        Event::fake([
            PengajuanApproved::class,
            PengajuanNomorGenerated::class,
            PengajuanStatusChanged::class,
            PengajuanSubmitted::class,
        ]);

        $this->actingAs($this->guru)->post(route('pengajuan.store'), [
            'blanko_id' => $this->blanko->id,
            'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
            'nama_kegiatan' => 'Rapat Dinas',
            'jenis_surat' => 'Surat Tugas',
            'aksi' => 'submit',
        ])->assertRedirect();

        $pengajuan = PengajuanSurat::latest()->first();
        expect($pengajuan->status)->toBe(StatusPengajuan::DIAJUKAN)
            ->and($pengajuan->current_step)->toBe(1);

        $this->actingAs($this->admin)->post(route('approval.verify', $pengajuan), [
            'catatan' => 'Data Lengkap',
        ])->assertRedirect();

        $pengajuan->refresh();
        expect($pengajuan->status)->toBe(StatusPengajuan::DIPROSES)
            ->and($pengajuan->current_step)->toBe(2)
            ->and($pengajuan->reviewed_by)->toBe($this->admin->id);

        $this->actingAs($this->pimpinan)->post(route('approval.approve', $pengajuan), [
            'catatan' => 'Setujui',
        ])->assertRedirect();

        $pengajuan->refresh();
        expect($pengajuan->status)->toBe(StatusPengajuan::DISETUJUI)
            ->and($pengajuan->current_step)->toBe(3)
            ->and($pengajuan->nomor_surat)->not->toBeNull()
            ->and($pengajuan->reviewed_by)->toBe($this->pimpinan->id);

        $logs = ApprovalLog::where('pengajuan_id', $pengajuan->id)->orderBy('step')->get();
        expect($logs)->toHaveCount(2)
            ->and($logs[0]->step)->toBe(1)
            ->and($logs[0]->approver_id)->toBe($this->admin->id)
            ->and($logs[1]->step)->toBe(2)
            ->and($logs[1]->approver_id)->toBe($this->pimpinan->id);

        Event::assertDispatched(PengajuanSubmitted::class);
        Event::assertDispatched(PengajuanApproved::class, 2);
        Event::assertDispatched(PengajuanNomorGenerated::class);
        Event::assertDispatched(PengajuanStatusChanged::class, 5);
    });
});

describe('Security & Authorization', function () {

    test('Guru Tidak Bisa Akses Pengajuan Guru Lain (IDOR Protection)', function () {
        $guruB = User::factory()->create(['role_id' => Role::where('name', 'guru')->value('id')]);

        $pengajuan = PengajuanSurat::factory()->create([
            'user_id' => $guruB->id,
            'blanko_id' => $this->blanko->id,
        ]);

        $this->actingAs($this->guru)
            ->get(route('pengajuan.show', $pengajuan))
            ->assertForbidden();
    });

    test('Guru Tidak Bisa Submit Pengajuan Orang Lain', function () {
        $guruB = User::factory()->create(['role_id' => Role::where('name', 'guru')->value('id')]);

        $pengajuan = PengajuanSurat::factory()->create([
            'user_id' => $guruB->id,
            'blanko_id' => $this->blanko->id,
            'status' => StatusPengajuan::DRAFT,
        ]);

        $this->actingAs($this->guru)
            ->post(route('pengajuan.submit', $pengajuan))
            ->assertForbidden();

        expect($pengajuan->fresh()->status)->toBe(StatusPengajuan::DRAFT);
    });

    test('Admin Tidak Bisa Approve Final Tanpa Pimpinan', function () {
        $pengajuan = PengajuanSurat::factory()->diproses()->create([
            'user_id' => $this->guru->id,
            'blanko_id' => $this->blanko->id,
        ]);

        $this->actingAs($this->admin)
            ->post(route('approval.approve', $pengajuan))
            ->assertForbidden();

        expect($pengajuan->fresh()->status)->toBe(StatusPengajuan::DIPROSES);
    });
});

describe('Draft Management', function () {

    test('broadcasts to admin tu and logs changes when a guru updates a draft', function () {
        $pengajuan = PengajuanSurat::factory()->create([
            'user_id' => $this->guru->id,
            'blanko_id' => $this->blanko->id,
            'panitia' => [
                ['nama' => 'Lama', 'jabatan' => 'Ketua', 'keterangan' => null],
            ],
        ]);

        Event::fake([PengajuanActivityChanged::class]);

        $this->actingAs($this->guru)
            ->put(route('pengajuan.update', $pengajuan), [
                'nama_kegiatan' => 'Rapat Koordinasi',
                'panitia' => [
                    ['nama' => 'Ayu', 'jabatan' => 'Ketua', 'keterangan' => 'Koordinasi'],
                    ['nama' => 'Budi', 'jabatan' => 'Anggota', 'keterangan' => null],
                ],
            ])
            ->assertRedirect(route('pengajuan.show', $pengajuan));

        expect($pengajuan->fresh()->panitia)->toBe([
            ['nama' => 'Ayu', 'jabatan' => 'Ketua', 'keterangan' => 'Koordinasi'],
            ['nama' => 'Budi', 'jabatan' => 'Anggota', 'keterangan' => null],
        ]);

        $this->assertDatabaseHas('log_aktivitas', [
            'aksi' => 'update_pengajuan',
            'model_type' => PengajuanSurat::class,
            'model_id' => $pengajuan->id,
            'user_id' => $this->guru->id,
        ]);

        Event::assertDispatched(
            PengajuanActivityChanged::class,
            fn (PengajuanActivityChanged $event) => $event->targetRole === 'admin_tu' && $event->aksi === 'updated',
        );
    });

    test('preserves existing panitia when a draft update omits panitia', function () {
        $pengajuan = PengajuanSurat::factory()->create([
            'user_id' => $this->guru->id,
            'blanko_id' => $this->blanko->id,
            'panitia' => [
                ['nama' => 'Ayu', 'jabatan' => 'Ketua', 'keterangan' => null],
                ['nama' => 'Budi', 'jabatan' => 'Anggota', 'keterangan' => null],
            ],
        ]);

        Event::fake([PengajuanActivityChanged::class]);

        $this->actingAs($this->guru)
            ->put(route('pengajuan.update', $pengajuan), [
                'nama_kegiatan' => 'Rapat Koordinasi',
            ])
            ->assertRedirect(route('pengajuan.show', $pengajuan));

        expect($pengajuan->fresh()->panitia)->toBe([
            ['nama' => 'Ayu', 'jabatan' => 'Ketua', 'keterangan' => null],
            ['nama' => 'Budi', 'jabatan' => 'Anggota', 'keterangan' => null],
        ]);
    });

    test('broadcasts to admin tu and logs deletion of a draft', function () {
        $pengajuan = PengajuanSurat::factory()->create([
            'user_id' => $this->guru->id,
            'blanko_id' => $this->blanko->id,
        ]);

        Event::fake([PengajuanActivityChanged::class]);

        $this->actingAs($this->guru)
            ->delete(route('pengajuan.destroy', $pengajuan))
            ->assertRedirect(route('pengajuan.index'));

        $this->assertModelMissing($pengajuan);
        
        $this->assertDatabaseHas('log_aktivitas', [
            'aksi' => 'hapus_pengajuan',
            'model_type' => PengajuanSurat::class,
            'model_id' => $pengajuan->id,
            'user_id' => $this->guru->id,
        ]);

        Event::assertDispatched(
            PengajuanActivityChanged::class,
            fn (PengajuanActivityChanged $event) => $event->targetRole === 'admin_tu' && $event->aksi === 'deleted',
        );
    });
});

describe('Document Generation', function () {

    test('Guru Dapat Membuka Detail Pengajuan Dengan Lampiran', function () {
        $pengajuan = PengajuanSurat::factory()->create([
            'user_id' => $this->guru->id,
            'blanko_id' => $this->blanko->id,
        ]);
        
        $pengajuan->lampiran()->create([
            'nama_file' => 'surat-pendukung.pdf',
            'path' => 'lampiran/pengajuan/surat-pendukung.pdf',
            'mime_type' => 'application/pdf',
            'ukuran' => 1024,
        ]);

        $this->actingAs($this->guru)
            ->get(route('pengajuan.show', $pengajuan))
            ->assertOk()
            ->assertSee('surat-pendukung.pdf');
    });

    test('renders an approved letter with teacher data and committee without QR content', function () {
        $this->guru->update([
            'nip' => '198701012010011001',
            'jabatan' => 'Guru Matematika',
        ]);

        $panitia = [
            ['nama' => 'Ayu', 'jabatan' => 'Ketua', 'keterangan' => 'Koordinasi'],
            ['nama' => 'Budi', 'jabatan' => 'Anggota', 'keterangan' => null],
        ];
        
        $pengajuan = PengajuanSurat::factory()->disetujui()->create([
            'user_id' => $this->guru->id,
            'blanko_id' => $this->blanko->id,
            'nama_kegiatan' => 'Kegiatan PDF',
            'panitia' => $panitia,
        ]);

        $html = view('pengajuan.pdf', ['pengajuan' => $pengajuan])->render();

        expect($html)->toContain('Data Guru Pengaju')
            ->toContain('198701012010011001')
            ->toContain('Guru Matematika')
            ->toContain('Kegiatan PDF')
            ->toContain('Ayu')
            ->toContain('Ketua')
            ->not->toContain('QR');

        $response = $this->actingAs($this->guru)
            ->get(route('pengajuan.pdf', $pengajuan));

        $response->assertOk()->assertHeader('content-type', 'application/pdf');
        expect($response->getContent())->toStartWith('%PDF');
    });
});

describe('Event Dispatching', function () {

    test('Event PengajuanSubmitted Ter-Dispatch Saat Submit', function () {
        Event::fake([PengajuanStatusChanged::class, PengajuanSubmitted::class]);

        $this->actingAs($this->guru)->post(route('pengajuan.store'), [
            'blanko_id' => $this->blanko->id,
            'tanggal_pelaksanaan' => now()->addDays(3)->format('Y-m-d'),
            'nama_kegiatan' => 'Rapat',
            'jenis_surat' => 'Surat Tugas',
            'aksi' => 'submit',
        ])->assertRedirect();

        Event::assertDispatched(PengajuanSubmitted::class);
    });

    test('Event PengajuanApproved Ter-Dispatch di Kedua Step', function () {
        Event::fake([PengajuanApproved::class, PengajuanStatusChanged::class]);

        $pengajuan = PengajuanSurat::factory()->diajukan()->create([
            'user_id' => $this->guru->id,
            'blanko_id' => $this->blanko->id,
        ]);

        $this->actingAs($this->admin)
            ->post(route('approval.verify', $pengajuan))
            ->assertRedirect();
        Event::assertDispatched(PengajuanApproved::class, fn ($e) => $e->step === 1);

        $this->actingAs($this->pimpinan)
            ->post(route('approval.approve', $pengajuan->fresh()))
            ->assertRedirect();
        Event::assertDispatched(PengajuanApproved::class, fn ($e) => $e->step === 2);
    });
});