<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            PengaturanSeeder::class,
            BlankoSuratSeeder::class,

            UserSeeder::class,
            DemoUsersSeeder::class,
            DemoPengajuanSeeder::class,
        ]);
    }
}
