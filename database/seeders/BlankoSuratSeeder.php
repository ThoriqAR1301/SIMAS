<?php

namespace Database\Seeders;

use App\Models\BlankoSurat;
use Illuminate\Database\Seeder;

class BlankoSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlankoSurat::updateOrCreate(
            ['kode' => 'ST'],
            [
                'nama' => 'Surat Tugas',
                'jenis' => 'Surat Tugas',
                'is_active' => true,
            ],
        );
    }
}
