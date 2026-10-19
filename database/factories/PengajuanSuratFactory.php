<?php

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
        return [
            'nomor_pengajuan' => 'PGJ/'.now()->year.'/'.now()->format('m').'/'.str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
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
        return $this->state([
            'status' => StatusPengajuan::DIAJUKAN,
            'current_step' => 1,
        ]);
    }

    public function diproses(): static
    {
        return $this->state([
            'status' => StatusPengajuan::DIPROSES,
            'current_step' => 2,
        ]);
    }

    public function disetujui(): static
    {
        return $this->state([
            'status' => StatusPengajuan::DISETUJUI,
            'current_step' => 3,
            'nomor_surat' => '001/TEST/TU/X/'.now()->year,
            'qr_token' => (string) Str::uuid(),
        ]);
    }
}
