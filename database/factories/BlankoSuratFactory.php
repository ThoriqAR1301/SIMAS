<?php

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
            'nama' => 'Blanko '.$nama,
            'deskripsi' => fake()->sentence(),
            'jenis' => 'tugas',
            'requires_lampiran' => false,
            'requires_surat_awalan' => false,
            'is_active' => true,
        ];
    }
}
