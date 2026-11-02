<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class Peran
{
    public static function data($user = null): array
    {
        $user ??= auth()->user();
        $role = $user?->role;

        if (is_string($role)) {
            $d = json_decode($role, true);
            if (is_array($d)) $role = $d;
        }
        if ($role instanceof Model) $role = $role->toArray();
        if (is_object($role)) $role = (array) $role;

        if (is_array($role)) {
            $name  = (string) ($role['name'] ?? '');
            $label = (string) ($role['display_name'] ?? $name);
        } else {
            $name = $label = (string) $role;
        }

        return ['name' => $name, 'label' => $label ?: 'User'];
    }

    public static function isAdmin($user = null): bool
    {
        return in_array(self::data($user)['name'], ['admin_tu', 'pimpinan']);
    }
}