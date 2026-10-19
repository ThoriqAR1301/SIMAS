<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('role.{roleName}', function (User $user, string $roleName) {
    return $user->role?->name === $roleName;
});

Broadcast::channel('user.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});
