<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'surat.format_nomor' => '{seq}/{kode}/{unit}/{romawi}/{tahun}',
            'surat.kode_unit' => 'TU',
            'surat.reset_seq' => 'tahunan',
        ] as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
