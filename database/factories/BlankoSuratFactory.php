<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlankoSuratFactory extends Factory
{
    public function definition(): array
    {
        $nama = fake()->unique()->words(2, true);

        return [
            'kode' => 'BLK-'.strtoupper(Str::random(5)),
            'nama' => 'Blanko '.ucwords($nama),
            'deskripsi' => fake()->sentence(),
            'jenis' => 'tugas',
            'requires_lampiran' => false,
            'requires_surat_awalan' => false,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function izinSakit(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis' => 'izin_sakit',
            'nama' => 'Blanko Izin Sakit',
            'kode' => 'IZIN-SAKIT',
            'requires_lampiran' => true,
            'requires_surat_awalan' => false,
        ]);
    }

    public function tugasDenganTemplate(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis' => 'tugas',
            'nama' => 'Blanko Surat Tugas',
            'kode' => 'SRT-TUGAS',
            'requires_lampiran' => false,
            'requires_surat_awalan' => true,
            'template_path' => 'templates/surat-tugas.docx',
        ]);
    }
}
