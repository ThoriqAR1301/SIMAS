<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password = null;

    /**
     * Mendefinisikan state default (User dengan data acak).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'nip' => fake()->unique()->numerify('################'),
            'jabatan' => fake()->jobTitle(),
            'is_active' => true,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function guru(): static
    {
        return $this->state(function (array $attributes) {
            $roleId = Role::where('name', 'guru')->value('id')
                ?? Role::create(['name' => 'guru', 'display_name' => 'Guru / Pegawai'])->id;

            return [
                'role_id' => $roleId,
                'jabatan' => 'Guru Mata Pelajaran',
            ];
        });
    }

    public function adminTu(): static
    {
        return $this->state(function (array $attributes) {
            $roleId = Role::where('name', 'admin_tu')->value('id')
                ?? Role::create(['name' => 'admin_tu', 'display_name' => 'Admin TU'])->id;

            return [
                'role_id' => $roleId,
                'jabatan' => 'Kepala Tata Usaha',
            ];
        });
    }

    public function pimpinan(): static
    {
        return $this->state(function (array $attributes) {
            $roleId = Role::where('name', 'pimpinan')->value('id')
                ?? Role::create(['name' => 'pimpinan', 'display_name' => 'Pimpinan'])->id;

            return [
                'role_id' => $roleId,
                'jabatan' => 'Kepala Sekolah',
            ];
        });
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
