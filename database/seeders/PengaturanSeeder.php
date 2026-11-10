<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'surat.format_nomor',
                'value' => '{seq}/{kode}/{unit}/{romawi}/{tahun}',
                'grup' => 'surat',
                'tipe' => 'string',
                'deskripsi' => 'Format penomoran surat resmi',
            ],
            [
                'key' => 'surat.kode_unit',
                'value' => 'TU',
                'grup' => 'surat',
                'tipe' => 'string',
                'deskripsi' => 'Kode unit tata usaha untuk penomoran surat',
            ],
            [
                'key' => 'surat.reset_seq',
                'value' => 'tahunan',
                'grup' => 'surat',
                'tipe' => 'string',
                'deskripsi' => 'Periode reset nomor urut (bulanan/tahunan)',
            ],

            [
                'key' => 'notif.email_enabled',
                'value' => '1',
                'grup' => 'notif',
                'tipe' => 'bool',
                'deskripsi' => 'Aktifkan pengiriman notifikasi via Email',
            ],
            [
                'key' => 'notif.realtime_enabled',
                'value' => '1',
                'grup' => 'notif',
                'tipe' => 'bool',
                'deskripsi' => 'Aktifkan notifikasi realtime (WebSocket)',
            ],

            [
                'key' => 'retensi.surat_izin_tahun',
                'value' => '3',
                'grup' => 'retensi',
                'tipe' => 'int',
                'deskripsi' => 'Masa simpan file surat izin (dalam tahun)',
            ],
            [
                'key' => 'retensi.surat_masuk_tahun',
                'value' => '5',
                'grup' => 'retensi',
                'tipe' => 'int',
                'deskripsi' => 'Masa simpan file surat masuk (dalam tahun)',
            ],
            [
                'key' => 'retensi.log_tahun',
                'value' => '2',
                'grup' => 'retensi',
                'tipe' => 'int',
                'deskripsi' => 'Masa simpan log aktivitas sistem (dalam tahun)',
            ],

            [
                'key' => 'laporan.rekap_mingguan_hari',
                'value' => 'senin',
                'grup' => 'laporan',
                'tipe' => 'string',
                'deskripsi' => 'Hari pengiriman rekap mingguan otomatis',
            ],
            [
                'key' => 'laporan.rekap_mingguan_jam',
                'value' => '07:00',
                'grup' => 'laporan',
                'tipe' => 'string',
                'deskripsi' => 'Jam pengiriman rekap mingguan otomatis',
            ],
        ];

        DB::transaction(function () use ($settings) {
            foreach ($settings as $setting) {
                Pengaturan::updateOrCreate(
                    ['key' => $setting['key']],
                    $setting
                );
            }
        });

        $this->command->info('✅ Berhasil mengisi '.count($settings).' data master Pengaturan');
    }
}
