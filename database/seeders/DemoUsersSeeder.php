<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Aesa Nurhaliza, S.Kom.',
                'email' => 'aesa@simas.test',
                'nip' => '198501012010011001',
                'jabatan' => 'Kepala Tata Usaha',
                'no_hp' => '081234567890',
                'role_name' => 'admin_tu',
            ],
            [
                'name' => 'Rina Marlina, A.Md.',
                'email' => 'rina@simas.test',
                'nip' => '199002022015022002',
                'jabatan' => 'Staf Tata Usaha',
                'no_hp' => '081234567891',
                'role_name' => 'admin_tu',
            ],

            [
                'name' => 'Dr. H. Bambang Sutrisno, M.Pd.',
                'email' => 'kepsek@simas.test',
                'nip' => '197001011995011001',
                'jabatan' => 'Kepala Sekolah',
                'no_hp' => '081234567892',
                'role_name' => 'pimpinan',
            ],
            [
                'name' => 'Dra. Siti Aminah, M.Pd.',
                'email' => 'wakasek@simas.test',
                'nip' => '197505051998022002',
                'jabatan' => 'Wakil Kepala Sekolah',
                'no_hp' => '081234567893',
                'role_name' => 'pimpinan',
            ],

            [
                'name' => 'Ahmad Fauzi, S.Pd.',
                'email' => 'ahmad@simas.test',
                'nip' => '198801012012011003',
                'jabatan' => 'Guru Matematika',
                'no_hp' => '081234567894',
                'role_name' => 'guru',
            ],
            [
                'name' => 'Dewi Lestari, S.Pd.',
                'email' => 'dewi@simas.test',
                'nip' => '199003032014022004',
                'jabatan' => 'Guru Bahasa Indonesia',
                'no_hp' => '081234567895',
                'role_name' => 'guru',
            ],
            [
                'name' => 'Rudi Hartono, S.Kom.',
                'email' => 'rudi@simas.test',
                'nip' => '199205052016011005',
                'jabatan' => 'Guru Informatika',
                'no_hp' => '081234567896',
                'role_name' => 'guru',
            ],
            [
                'name' => 'Sari Indah, S.Pd.',
                'email' => 'sari@simas.test',
                'nip' => '199507072018022006',
                'jabatan' => 'Guru Biologi',
                'no_hp' => '081234567897',
                'role_name' => 'guru',
            ],
        ];

        $roles = Role::pluck('id', 'name');

        $hashedPassword = Hash::make('password');

        DB::transaction(function () use ($users, $roles, $hashedPassword) {
            foreach ($users as $user) {
                $roleId = $roles[$user['role_name']] ?? null;

                if (! $roleId) {
                    $this->command->error("❌ Role '{$user['role_name']}' tidak ditemukan untuk {$user['email']}");

                    continue;
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

        $this->command->info('✅ Demo Users berhasil dibuat!');
        $this->command->line('Terdapat 2 Admin TU, 2 Pimpinan, dan 4 Guru');
        $this->command->line('Semua akun menggunakan password : "password"');
    }
}
