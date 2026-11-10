<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'guru',
                'display_name' => 'Guru / Pegawai',
                'deskripsi' => 'Hak akses untuk mengajukan surat, menginput surat masuk, dan melihat notifikasi',
            ],
            [
                'name' => 'admin_tu',
                'display_name' => 'Admin Tata Usaha',
                'deskripsi' => 'Hak akses untuk memverifikasi pengajuan, mengelola surat masuk, folder, akun, dan pengaturan',
            ],
            [
                'name' => 'pimpinan',
                'display_name' => 'Pimpinan / Kepala Sekolah',
                'deskripsi' => 'Hak akses untuk memberikan persetujuan final, memantau laporan, dan disposisi surat',
            ],
        ];

        DB::transaction(function () use ($roles) {
            foreach ($roles as $role) {
                Role::updateOrCreate(
                    ['name' => $role['name']],
                    $role
                );
            }
        });

        $this->command->info('✅ Berhasil mengisi '.count($roles).' data master Role');
    }
}
