<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Aesa Nurhaliza, S.Kom.',
                'email' => 'aesa@simas.test',
                'password' => 'password',
                'role' => 'admin_tu',
            ],
            [
                'name' => 'Rina Marlina, A.Md.',
                'email' => 'rina@simas.test',
                'password' => 'password',
                'role' => 'admin_tu',
            ],

            [
                'name' => 'Dr. H. Bambang Sutrisno, M.Pd.',
                'email' => 'kepsek@simas.test',
                'password' => 'password',
                'role' => 'pimpinan',
            ],
            [
                'name' => 'Dra. Siti Aminah, M.Pd.',
                'email' => 'wakasek@simas.test',
                'password' => 'password',
                'role' => 'pimpinan',
            ],

            [
                'name' => 'Ahmad Fauzi, S.Pd.',
                'email' => 'ahmad@simas.test',
                'password' => 'password',
                'role' => 'guru',
            ],
            [
                'name' => 'Dewi Lestari, S.Pd.',
                'email' => 'dewi@simas.test',
                'password' => 'password',
                'role' => 'guru',
            ],
            [
                'name' => 'Rudi Hartono, S.Kom.',
                'email' => 'rudi@simas.test',
                'password' => 'password',
                'role' => 'guru',
            ],
            [
                'name' => 'Sari Indah, S.Pd.',
                'email' => 'sari@simas.test',
                'password' => 'password',
                'role' => 'guru',
            ],
        ];

        foreach ($data as $d) {
            $role = Role::where('name', $d['role'])->first();
            if (! $role) {
                continue;
            }

            User::updateOrCreate(
                ['email' => $d['email']],
                [
                    'name' => $d['name'],
                    'password' => Hash::make($d['password']),
                    'role_id' => $role->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Demo Users Berhasil Dibuat (Password : "password")');
    }
}
