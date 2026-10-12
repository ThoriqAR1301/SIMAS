<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $guru = Role::where('name', 'guru')->firstOrFail();

        $guru->permissions()->sync(
            Permission::whereIn('name', ['pengajuan.create', 'pengajuan.update'])->pluck('id'),
        );
    }
}
