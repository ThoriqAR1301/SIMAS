<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\StatusPengajuan;
use App\Models\BlankoSurat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PengajuanSuratFactory extends Factory
{
    public function definition(): array
    {
        $seq = Str::padLeft((string) fake()->unique()->numberBetween(1, 9999), 4, '0');

        return [
            'nomor_pengajuan' => 'PGJ/'.now()->year.'/'.now()->format('m').'/'.$seq,
            'nomor_surat' => null,
            'user_id' => User::factory(),
            'blanko_id' => BlankoSurat::factory(),
            'tanggal_pelaksanaan' => now()->addDays(3),
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '12:00',
            'tempat' => fake()->city(),
            'nama_kegiatan' => fake()->sentence(3),
            'jenis_surat' => 'Surat Tugas',
            'keterangan' => fake()->paragraph(),
            'status' => StatusPengajuan::DRAFT,
            'current_step' => 0,
        ];
    }

    public function diajukan(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusPengajuan::DIAJUKAN,
            'current_step' => 1,
        ]);
    }

    public function diproses(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusPengajuan::DIPROSES,
            'current_step' => 2,
        ]);
    }

    public function disetujui(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusPengajuan::DISETUJUI,
            'current_step' => 3,
            'nomor_surat' => '001/TEST/TU/X/'.now()->year,
        ]);
    }

    public function ditolak(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusPengajuan::DITOLAK,
            'current_step' => 1,
            'catatan_reviewer' => 'Ditolak karena data tidak lengkap.',
        ]);
    }

    public function revisi(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusPengajuan::DRAFT,
            'current_step' => 0,
            'catatan_reviewer' => 'Mohon perbaiki tanggal pelaksanaan.',
        ]);
    }

    public function withPanitia(): static
    {
        return $this->state(fn (array $attributes) => [
            'panitia' => [
                ['nama' => 'Ahmad Fauzi, S.Pd.', 'jabatan' => 'Ketua', 'keterangan' => 'Penanggung jawab'],
                ['nama' => 'Dewi Lestari, S.Pd.', 'jabatan' => 'Sekretaris', 'keterangan' => 'Notulen'],
                ['nama' => 'Rudi Hartono, S.Kom.', 'jabatan' => 'Anggota', 'keterangan' => 'Dokumentasi'],
            ],
        ]);
    }
}
