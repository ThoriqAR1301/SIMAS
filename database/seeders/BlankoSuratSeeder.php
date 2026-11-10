<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BlankoSurat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlankoSuratSeeder extends Seeder
{
    public function run(): void
    {
        $blankos = [
            [
                'kode' => 'ST',
                'nama' => 'Surat Tugas',
                'jenis' => 'tugas',
                'deskripsi' => 'Surat penugasan resmi untuk guru/pegawai',
                'requires_lampiran' => false,
                'requires_surat_awalan' => false,
                'is_active' => true,
            ],
            [
                'kode' => 'SU',
                'nama' => 'Surat Undangan',
                'jenis' => 'undangan',
                'deskripsi' => 'Surat undangan rapat atau kegiatan sekolah',
                'requires_lampiran' => false,
                'requires_surat_awalan' => false,
                'is_active' => true,
            ],
            [
                'kode' => 'SK',
                'nama' => 'Surat Keterangan',
                'jenis' => 'keterangan',
                'deskripsi' => 'Surat keterangan aktif sekolah atau keperluan lainnya',
                'requires_lampiran' => false,
                'requires_surat_awalan' => false,
                'is_active' => true,
            ],
            [
                'kode' => 'IZIN-SAKIT',
                'nama' => 'Surat Izin Sakit',
                'jenis' => 'izin_sakit',
                'deskripsi' => 'Izin tidak masuk karena sakit. Wajib upload surat keterangan dokter',
                'requires_lampiran' => true,
                'requires_surat_awalan' => false,
                'is_active' => true,
            ],
            [
                'kode' => 'IZIN-NON-SAKIT',
                'nama' => 'Surat Izin Non-Sakit',
                'jenis' => 'izin_non_sakit',
                'deskripsi' => 'Izin tidak masuk karena keperluan pribadi. Wajib pakai surat awalan',
                'requires_lampiran' => true,
                'requires_surat_awalan' => true,
                'is_active' => true,
            ],
            [
                'kode' => 'IZIN-LAIN',
                'nama' => 'Surat Izin Keperluan Lain',
                'jenis' => 'keperluan_lain',
                'deskripsi' => 'Izin untuk keperluan lain di luar sakit/non-sakit',
                'requires_lampiran' => true,
                'requires_surat_awalan' => true,
                'is_active' => true,
            ],
        ];

        DB::transaction(function () use ($blankos) {
            foreach ($blankos as $blanko) {
                BlankoSurat::updateOrCreate(
                    ['kode' => $blanko['kode']],
                    $blanko
                );
            }
        });

        $this->command->info('✅ Berhasil mengisi '.count($blankos).' data master Blanko Surat');
    }
}
