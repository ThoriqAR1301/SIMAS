<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Peran
{
    /**
     * Mendapatkan nama dan label peran dari user.
     *
     * @return array{name: string, label: string}
     */
    public static function data(?User $user = null): array
    {
        $user ??= Auth::user();

        if (! $user) {
            return ['name' => 'guest', 'label' => 'Guest'];
        }

        $role = $user->role;

        if (is_string($role)) {
            $decoded = json_decode($role, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $role = $decoded;
            }
        }

        if ($role instanceof Model) {
            $role = $role->toArray();
        } elseif (is_object($role)) {
            $role = (array) $role;
        }

        if (is_array($role)) {
            $name = (string) ($role['name'] ?? '');
            $label = (string) ($role['display_name'] ?? $name);
        } else {
            $name = $label = (string) $role;
        }

        return [
            'name' => $name ?: 'user',
            'label' => $label ?: 'User',
        ];
    }

    public static function isAdmin(?User $user = null): bool
    {
        return in_array(self::data($user)['name'], ['admin_tu', 'pimpinan'], true);
    }

    public static function isPimpinan(?User $user = null): bool
    {
        return self::data($user)['name'] === 'pimpinan';
    }

    public static function isAdminTu(?User $user = null): bool
    {
        return self::data($user)['name'] === 'admin_tu';
    }

    public static function isGuru(?User $user = null): bool
    {
        return self::data($user)['name'] === 'guru';
    }
}
