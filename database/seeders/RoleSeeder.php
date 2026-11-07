<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'guru', 'display_name' => 'Guru', 'deskripsi' => 'Pengaju surat'],
            ['name' => 'admin_tu', 'display_name' => 'Admin TU', 'deskripsi' => 'Verifikator pengajuan'],
            ['name' => 'pimpinan', 'display_name' => 'Pimpinan', 'deskripsi' => 'Pemberi persetujuan akhir'],
        ] as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
