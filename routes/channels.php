<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('role.{roleName}', function (User $user, string $roleName): bool {
    $user->loadMissing('role');

    return $user->hasRole($roleName);
});

Broadcast::channel('user.{userId}', function (User $user, int $userId): bool {
    return $user->id === $userId;
});
