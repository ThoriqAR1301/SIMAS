<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin SIMAS',
                'email' => 'superadmin@simas.test',
                'password' => 'password',
                'role_name' => 'admin_tu',
                'nip' => '197001010000000001',
                'jabatan' => 'Administrator Sistem',
                'no_hp' => '080000000001',
            ],
            [
                'name' => 'Admin Tata Usaha',
                'email' => 'admin@simas.test',
                'password' => 'password',
                'role_name' => 'admin_tu',
                'nip' => '197001010000000002',
                'jabatan' => 'Kepala Tata Usaha',
                'no_hp' => '080000000002',
            ],
            [
                'name' => 'Pimpinan Sekolah',
                'email' => 'pimpinan@simas.test',
                'password' => 'password',
                'role_name' => 'pimpinan',
                'nip' => '197001010000000003',
                'jabatan' => 'Kepala Sekolah',
                'no_hp' => '080000000003',
            ],
            [
                'name' => 'Guru Contoh',
                'email' => 'guru@simas.test',
                'password' => 'password',
                'role_name' => 'guru',
                'nip' => '198501010000000004',
                'jabatan' => 'Guru Mata Pelajaran',
                'no_hp' => '080000000004',
            ],
        ];

        $roles = Role::pluck('id', 'name');

        $hashedPassword = Hash::make('password');

        DB::transaction(function () use ($users, $roles, $hashedPassword) {
            foreach ($users as $user) {
                $roleId = $roles[$user['role_name']] ?? null;

                if (! $roleId) {
                    $this->command->error("❌ Role '{$user['role_name']}' tidak ditemukan. Seeder dibatalkan");

                    return;
                }

                User::updateOrCreate(
                    ['email' => $user['email']],
                    [
                        'name' => $user['name'],
                        'password' => $hashedPassword,
                        'role_id' => $roleId,
                        'nip' => $user['nip'],
                        'jabatan' => $user['jabatan'],
                        'no_hp' => $user['no_hp'],
                        'is_active' => true,
                        'email_verified_at' => now(),
                    ]
                );
            }
        });

        $this->command->info('✅ Berhasil membuat '.count($users).' Akun Utama (UserSeeder)');
        $this->command->line('Semua akun menggunakan password default : "password"');
    }
}
