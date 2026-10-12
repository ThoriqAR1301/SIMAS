<?php

namespace Database\Seeders;

use App\Enums\StatusPengajuan;
use App\Models\ApprovalLog;
use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoPengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $guru = User::whereHas('role', fn ($q) => $q->where('name', 'guru'))->first();
        $admin = User::whereHas('role', fn ($q) => $q->where('name', 'admin_tu'))->first();
        $pimpinan = User::whereHas('role', fn ($q) => $q->where('name', 'pimpinan'))->first();
        $blanko = BlankoSurat::first();

        if (!$guru || !$blanko) {
            $this->command->warn('Skip Demo : User/Blanko Belum Ada');
            return;
        }

        PengajuanSurat::factory()->create([
            'user_id' => $guru->id,
            'blanko_id' => $blanko->id,
            'nama_kegiatan' => 'Draft: Rapat MGMP',
            'status' => StatusPengajuan::DRAFT,
        ]);

        PengajuanSurat::factory()->diajukan()->create([
            'user_id' => $guru->id,
            'blanko_id' => $blanko->id,
            'nama_kegiatan' => 'Menunggu Admin : Study Tour',
        ]);

        $p2 = PengajuanSurat::factory()->diproses()->create([
            'user_id' => $guru->id,
            'blanko_id' => $blanko->id,
            'nama_kegiatan' => 'Menunggu Pimpinan : Workshop',
        ]);
        ApprovalLog::create([
            'pengajuan_id' => $p2->id,
            'step' => 1,
            'approver_id' => $admin->id,
            'aksi' => 'approve',
            'catatan' => 'Data lengkap, diteruskan.',
            'acted_at' => now()->subHours(2),
        ]);

        $p3 = PengajuanSurat::factory()->disetujui()->create([
            'user_id' => $guru->id,
            'blanko_id' => $blanko->id,
            'nama_kegiatan' => 'Disetujui : Bimbingan Teknis',
        ]);
        ApprovalLog::create([
            'pengajuan_id' => $p3->id,
            'step' => 1, 'approver_id' => $admin->id,
            'aksi' => 'approve', 'catatan' => 'OK', 'acted_at' => now()->subDays(1),
        ]);
        ApprovalLog::create([
            'pengajuan_id' => $p3->id,
            'step' => 2, 'approver_id' => $pimpinan->id,
            'aksi' => 'approve', 'catatan' => 'Setujui.', 'acted_at' => now()->subHours(5),
        ]);

        PengajuanSurat::factory()->create([
            'user_id' => $guru->id,
            'blanko_id' => $blanko->id,
            'nama_kegiatan' => 'Ditolak : Cuti Tahunan',
            'status' => StatusPengajuan::DITOLAK,
            'current_step' => 1,
            'catatan_reviewer' => 'Tanggal Bertabrakan Dengan Jadwal Ujian',
        ]);

        $this->command->info('✅ Demo Data Pengajuan Berhasil Dibuat');
    }
}