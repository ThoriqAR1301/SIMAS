<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'pengajuan.create', 'display_name' => 'Membuat pengajuan'],
            ['name' => 'pengajuan.update', 'display_name' => 'Mengubah pengajuan'],
        ] as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
