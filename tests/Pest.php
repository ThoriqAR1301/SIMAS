<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;



pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Unit');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

expect()->extend('toBeSuccess', function () {
    return $this->toBeInstanceOf(TestResponse::class)->assertOk();
});

expect()->extend('toBeRedirect', function () {
    return $this->toBeInstanceOf(TestResponse::class)->assertRedirect();
});

expect()->extend('toBeForbidden', function () {
    return $this->toBeInstanceOf(TestResponse::class)->assertForbidden();
});

expect()->extend('toBeNotFound', function () {
    return $this->toBeInstanceOf(TestResponse::class)->assertNotFound();
});

expect()->extend('toBeUnauthorized', function () {
    return $this->toBeInstanceOf(TestResponse::class)->assertUnauthorized();
});

function getUserByRole(string $roleName): User
{
    $role = Role::firstOrCreate(
        ['name' => $roleName],
        ['display_name' => ucwords(str_replace('_', ' ', $roleName))]
    );
    
    return User::factory()->create(['role_id' => $role->id]);
}

function asGuru(): User
{
    $user = getUserByRole('guru');
    test()->actingAs($user);
    return $user;
}

function asAdminTu(): User
{
    $user = getUserByRole('admin_tu');
    test()->actingAs($user);
    return $user;
}

function asPimpinan(): User
{
    $user = getUserByRole('pimpinan');
    test()->actingAs($user);
    return $user;
}