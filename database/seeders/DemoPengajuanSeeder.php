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
        $guruList = User::whereHas('role', fn ($q) => $q->where('name', 'guru'))->get();
        $admin = User::whereHas('role', fn ($q) => $q->where('name', 'admin_tu'))->first();
        $pimpinan = User::whereHas('role', fn ($q) => $q->where('name', 'pimpinan'))->first();
        $blanko = BlankoSurat::first();

        if ($guruList->isEmpty() || !$admin || !$pimpinan || !$blanko) {
            $this->command->warn('⚠️  Skip DemoPengajuanSeeder : User/Blanko Belum Lengkap');
            return;
        }

        $drafts = [
            ['Rapat Koordinasi MGMP Matematika', 'Ahmad Fauzi, S.Pd.'],
            ['Workshop Kurikulum Merdeka', 'Dewi Lestari, S.Pd.'],
            ['Pelatihan Coding untuk Guru', 'Rudi Hartono, S.Kom.'],
        ];

        foreach ($drafts as [$kegiatan, $namaGuru]) {
            $guru = $guruList->firstWhere('name', $namaGuru) ?? $guruList->first();
            PengajuanSurat::factory()->create([
                'user_id' => $guru->id,
                'blanko_id' => $blanko->id,
                'nama_kegiatan' => $kegiatan,
                'status' => StatusPengajuan::DRAFT,
                'current_step' => 0,
                'created_at' => now()->subDays(rand(1, 5)),
            ]);
        }

        $menungguAdmin = [
            ['Study Tour Ke Yogyakarta', 'Dewi Lestari, S.Pd.', 'Surat Tugas'],
            ['Seminar Nasional Pendidikan', 'Sari Indah, S.Pd.', 'Surat Tugas'],
        ];

        foreach ($menungguAdmin as [$kegiatan, $namaGuru, $jenis]) {
            $guru = $guruList->firstWhere('name', $namaGuru) ?? $guruList->first();
            PengajuanSurat::factory()->diajukan()->create([
                'user_id' => $guru->id,
                'blanko_id' => $blanko->id,
                'nama_kegiatan' => $kegiatan,
                'jenis_surat' => $jenis,
                'created_at' => now()->subDays(rand(1, 3)),
            ]);
        }

        $menungguPimpinan = [
            ['Bimbingan Teknis K13', 'Ahmad Fauzi, S.Pd.'],
            ['Lomba Inovasi Pembelajaran', 'Rudi Hartono, S.Kom.'],
        ];

        foreach ($menungguPimpinan as [$kegiatan, $namaGuru]) {
            $guru = $guruList->firstWhere('name', $namaGuru) ?? $guruList->first();

            $p = PengajuanSurat::factory()->diproses()->create([
                'user_id' => $guru->id,
                'blanko_id' => $blanko->id,
                'nama_kegiatan' => $kegiatan,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now()->subHours(rand(2, 24)),
                'created_at' => now()->subDays(rand(2, 4)),
            ]);

            ApprovalLog::create([
                'pengajuan_id' => $p->id,
                'step' => 1,
                'approver_id' => $admin->id,
                'aksi' => 'approve',
                'catatan' => 'Data Lengkap, Diteruskan Ke Pimpinan',
                'acted_at' => now()->subHours(rand(2, 24)),
            ]);
        }

        $disetujui = [
            ['IHT Penyusunan Modul Ajar', 'Dewi Lestari, S.Pd.'],
            ['Pelatihan P5 Untuk Guru', 'Sari Indah, S.Pd.'],
            ['Kunjungan Industri Ke PT Telkom', 'Rudi Hartono, S.Kom.'],
        ];

        foreach ($disetujui as $index => [$kegiatan, $namaGuru]) {
            $guru = $guruList->firstWhere('name', $namaGuru) ?? $guruList->first();
            $seq = str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            $p = PengajuanSurat::factory()->disetujui()->create([
                'user_id' => $guru->id,
                'blanko_id' => $blanko->id,
                'nama_kegiatan' => $kegiatan,
                'nomor_surat' => "{$seq}/ST/TU/X/" . now()->year,
                'reviewed_by' => $pimpinan->id,
                'reviewed_at' => now()->subDays(rand(1, 5)),
                'created_at' => now()->subDays(rand(5, 10)),
            ]);

            ApprovalLog::create([
                'pengajuan_id' => $p->id,
                'step' => 1,
                'approver_id' => $admin->id,
                'aksi' => 'approve',
                'catatan' => 'Disetujui, Silakan Lanjut',
                'acted_at' => now()->subDays(rand(4, 6)),
            ]);

            ApprovalLog::create([
                'pengajuan_id' => $p->id,
                'step' => 2,
                'approver_id' => $pimpinan->id,
                'aksi' => 'approve',
                'catatan' => 'Setujui Dan Dilaksanakan Dengan Baik',
                'acted_at' => now()->subDays(rand(1, 3)),
            ]);
        }

        $ditolak = [
            ['Cuti Tahunan Bulan November', 'Ahmad Fauzi, S.Pd.', 'Bertabrakan Dengan Jadwal UAS'],
            ['Izin Mengikuti Webinar Luar', 'Sari Indah, S.Pd.', 'Tidak Ada Surat Undangan Resmi'],
        ];

        foreach ($ditolak as [$kegiatan, $namaGuru, $alasan]) {
            $guru = $guruList->firstWhere('name', $namaGuru) ?? $guruList->first();

            $p = PengajuanSurat::factory()->create([
                'user_id' => $guru->id,
                'blanko_id' => $blanko->id,
                'nama_kegiatan' => $kegiatan,
                'status' => StatusPengajuan::DITOLAK,
                'current_step' => 1,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now()->subDays(rand(1, 3)),
                'catatan_reviewer' => $alasan,
                'created_at' => now()->subDays(rand(3, 7)),
            ]);

            ApprovalLog::create([
                'pengajuan_id' => $p->id,
                'step' => 1,
                'approver_id' => $admin->id,
                'aksi' => 'reject',
                'catatan' => $alasan,
                'acted_at' => now()->subDays(rand(1, 3)),
            ]);
        }

        $guru = $guruList->first();
        $p = PengajuanSurat::factory()->create([
            'user_id' => $guru->id,
            'blanko_id' => $blanko->id,
            'nama_kegiatan' => 'Pendaftaran Lomba Guru Berprestasi',
            'status' => StatusPengajuan::DRAFT,
            'current_step' => 0,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now()->subDays(1),
            'catatan_reviewer' => 'Mohon Lampirkan Surat Undangan Resmi Dari Panitia',
            'created_at' => now()->subDays(2),
        ]);

        ApprovalLog::create([
            'pengajuan_id' => $p->id,
            'step' => 1,
            'approver_id' => $admin->id,
            'aksi' => 'revisi',
            'catatan' => 'Mohon Lampirkan Surat Undangan Resmi Dari Panitia',
            'acted_at' => now()->subDays(1),
        ]);

        $this->command->info('✅ Demo Pengajuan Berhasil : 3 Draft, 2 Menunggu Admin, 2 Menunggu Pimpinan, 3 Disetujui, 2 Ditolak, 1 Revisi');
    }
}
