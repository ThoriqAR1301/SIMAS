<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Test User', 'email' => 'test@example.com', 'role' => 'guru'],
            ['name' => 'Demo Admin TU', 'email' => 'admin@example.com', 'role' => 'admin_tu'],
            ['name' => 'Demo Pimpinan', 'email' => 'pimpinan@example.com', 'role' => 'pimpinan'],
        ] as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make(Str::random(40)),
                    'role_id' => Role::where('name', $user['role'])->value('id'),
                ],
            );
        }
    }
}
