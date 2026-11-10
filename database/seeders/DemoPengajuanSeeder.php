<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StatusPengajuan;
use App\Models\ApprovalLog;
use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoPengajuanSeeder extends Seeder
{
    public function run(): void
    {
        $guruList = User::whereHas('role', fn ($q) => $q->where('name', 'guru'))->get();
        $admin = User::whereHas('role', fn ($q) => $q->where('name', 'admin_tu'))->first();
        $pimpinan = User::whereHas('role', fn ($q) => $q->where('name', 'pimpinan'))->first();
        $blanko = BlankoSurat::first();

        if ($guruList->isEmpty() || ! $admin || ! $pimpinan || ! $blanko) {
            $this->command->warn('⚠️ Skip DemoPengajuanSeeder : User/Blanko belum lengkap');

            return;
        }

        DB::transaction(function () use ($guruList, $admin, $pimpinan, $blanko) {

            $this->seedDraft($guruList, $blanko);
            $this->seedMenungguAdmin($guruList, $blanko);
            $this->seedMenungguPimpinan($guruList, $blanko, $admin);
            $this->seedDisetujui($guruList, $blanko, $admin, $pimpinan);
            $this->seedDitolak($guruList, $blanko, $admin);
            $this->seedRevisi($guruList, $blanko, $admin);

        });

        $this->command->info('✅ Demo Pengajuan berhasil : 3 Draft, 2 Menunggu Admin, 2 Menunggu Pimpinan, 3 Disetujui, 2 Ditolak, 1 Revisi');
    }

    private function seedDraft(Collection $guruList, BlankoSurat $blanko): void
    {
        $data = [
            ['Rapat Koordinasi MGMP Matematika', 'Ahmad Fauzi, S.Pd.'],
            ['Workshop Kurikulum Merdeka', 'Dewi Lestari, S.Pd.'],
            ['Pelatihan Coding untuk Guru', 'Rudi Hartono, S.Kom.'],
        ];

        foreach ($data as [$kegiatan, $namaGuru]) {
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
    }

    private function seedMenungguAdmin(Collection $guruList, BlankoSurat $blanko): void
    {
        $data = [
            ['Study Tour Ke Yogyakarta', 'Dewi Lestari, S.Pd.', 'Surat Tugas'],
            ['Seminar Nasional Pendidikan', 'Sari Indah, S.Pd.', 'Surat Tugas'],
        ];

        foreach ($data as [$kegiatan, $namaGuru, $jenis]) {
            $guru = $guruList->firstWhere('name', $namaGuru) ?? $guruList->first();

            PengajuanSurat::factory()->diajukan()->create([
                'user_id' => $guru->id,
                'blanko_id' => $blanko->id,
                'nama_kegiatan' => $kegiatan,
                'jenis_surat' => $jenis,
                'created_at' => now()->subDays(rand(1, 3)),
            ]);
        }
    }

    private function seedMenungguPimpinan(Collection $guruList, BlankoSurat $blanko, User $admin): void
    {
        $data = [
            ['Bimbingan Teknis K13', 'Ahmad Fauzi, S.Pd.'],
            ['Lomba Inovasi Pembelajaran', 'Rudi Hartono, S.Kom.'],
        ];

        foreach ($data as [$kegiatan, $namaGuru]) {
            $guru = $guruList->firstWhere('name', $namaGuru) ?? $guruList->first();

            $p = PengajuanSurat::factory()->diproses()->create([
                'user_id' => $guru->id,
                'blanko_id' => $blanko->id,
                'nama_kegiatan' => $kegiatan,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now()->subHours(rand(2, 24)),
                'created_at' => now()->subDays(rand(2, 4)),
            ]);

            $this->catatLog($p, 1, $admin, 'approve', 'Data lengkap, diteruskan ke Pimpinan');
        }
    }

    private function seedDisetujui(Collection $guruList, BlankoSurat $blanko, User $admin, User $pimpinan): void
    {
        $data = [
            ['IHT Penyusunan Modul Ajar', 'Dewi Lestari, S.Pd.'],
            ['Pelatihan P5 Untuk Guru', 'Sari Indah, S.Pd.'],
            ['Kunjungan Industri Ke PT Telkom', 'Rudi Hartono, S.Kom.'],
        ];

        foreach ($data as $index => [$kegiatan, $namaGuru]) {
            $guru = $guruList->firstWhere('name', $namaGuru) ?? $guruList->first();
            $seq = str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);

            $p = PengajuanSurat::factory()->disetujui()->create([
                'user_id' => $guru->id,
                'blanko_id' => $blanko->id,
                'nama_kegiatan' => $kegiatan,
                'nomor_surat' => "{$seq}/ST/TU/X/".now()->year,
                'reviewed_by' => $pimpinan->id,
                'reviewed_at' => now()->subDays(rand(1, 5)),
                'created_at' => now()->subDays(rand(5, 10)),
            ]);

            $this->catatLog($p, 1, $admin, 'approve', 'Disetujui, silakan lanjut.', rand(4, 6));
            $this->catatLog($p, 2, $pimpinan, 'approve', 'Setujui dan dilaksanakan dengan baik.', rand(1, 3));
        }
    }

    private function seedDitolak(Collection $guruList, BlankoSurat $blanko, User $admin): void
    {
        $data = [
            ['Cuti Tahunan Bulan November', 'Ahmad Fauzi, S.Pd.', 'Bertabrakan dengan jadwal UAS'],
            ['Izin Mengikuti Webinar Luar', 'Sari Indah, S.Pd.', 'Tidak ada surat undangan resmi'],
        ];

        foreach ($data as [$kegiatan, $namaGuru, $alasan]) {
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

            $this->catatLog($p, 1, $admin, 'reject', $alasan, rand(1, 3));
        }
    }

    private function seedRevisi(Collection $guruList, BlankoSurat $blanko, User $admin): void
    {
        $guru = $guruList->first();

        $p = PengajuanSurat::factory()->create([
            'user_id' => $guru->id,
            'blanko_id' => $blanko->id,
            'nama_kegiatan' => 'Pendaftaran Lomba Guru Berprestasi',
            'status' => StatusPengajuan::DRAFT,
            'current_step' => 0,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now()->subDays(1),
            'catatan_reviewer' => 'Mohon lampirkan surat undangan resmi dari panitia',
            'created_at' => now()->subDays(2),
        ]);

        $this->catatLog($p, 1, $admin, 'revisi', 'Mohon lampirkan surat undangan resmi dari panitia', 1);
    }

    private function catatLog(PengajuanSurat $pengajuan, int $step, User $approver, string $aksi, string $catatan, int $daysAgo = 0): void
    {
        ApprovalLog::create([
            'pengajuan_id' => $pengajuan->id,
            'step' => $step,
            'approver_id' => $approver->id,
            'aksi' => $aksi,
            'catatan' => $catatan,
            'acted_at' => $daysAgo > 0 ? now()->subDays($daysAgo) : now()->subHours(rand(2, 24)),
        ]);
    }
}
