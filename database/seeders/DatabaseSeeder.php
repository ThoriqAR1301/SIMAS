<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->command->info('🚀 Memulai proses seeding database SIMAS...');

        $this->command->info('📦 Mengisi data master inti...');

        DB::transaction(function () {
            $this->call([
                RoleSeeder::class,
                PermissionSeeder::class,
                RolePermissionSeeder::class,
                PengaturanSeeder::class,
                BlankoSuratSeeder::class,
                UserSeeder::class,
            ]);
        });

        $this->command->info('✅ Data master inti berhasil dibuat');

        if (app()->environment(['local', 'testing'])) {
            $this->command->warn('🧪 Environment terdeteksi: LOCAL/TESTING. Mengisi data demo...');

            DB::transaction(function () {
                $this->call([
                    DemoUsersSeeder::class,
                    DemoPengajuanSeeder::class,
                ]);
            });

            $this->command->info('✅ Data demo (Guru, Pengajuan) berhasil dibuat');
        } else {
            $this->command->warn('⚠️ Environment PRODUCTION terdeteksi. Data demo dilewati demi keamanan');
        }

        $this->command->info('🎉 Semua proses seeding selesai!');
    }
}
