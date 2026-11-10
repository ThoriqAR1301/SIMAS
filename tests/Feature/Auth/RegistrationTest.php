<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;

describe('Registration', function () {

    beforeEach(function () {
        $this->seed(RoleSeeder::class);
    });

    test('registration screen can be rendered', function () {
        $response = $this->get('/register');
        $response->assertStatus(200);
    });

    test('new users can register successfully', function () {
        $guruRole = Role::where('name', 'guru')->firstOrFail();

        $response = $this->post('/register', [
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@simas.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'ahmad@simas.test',
            'role_id' => $guruRole->id,
            'is_active' => true,
        ]);

        $user = User::where('email', 'ahmad@simas.test')->first();
        expect($user->isGuru())->toBeTrue();
    });

    test('registration reports when the guru role is missing', function () {
        Role::query()->delete();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    });

    test('email is sanitized to lowercase and trimmed during registration', function () {
        $this->post('/register', [
            'name' => 'Ahmad Fauzi',
            'email' => '  AHMAD@SIMAS.TEST  ',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'ahmad@simas.test',
        ]);
    });

    test('registration fails if email is already taken', function () {
        User::factory()->create([
            'email' => 'ahmad@simas.test',
            'role_id' => $this->guruRole->id,
        ]);

        $response = $this->post('/register', [
            'name' => 'Ahmad Fauzi Baru',
            'email' => 'ahmad@simas.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    });

    test('registration fails if password confirmation does not match', function () {
        $response = $this->post('/register', [
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@simas.test',
            'password' => 'password123',
            'password_confirmation' => 'password-berbeda',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    });

    test('registration fails if password is too short', function () {
        $response = $this->post('/register', [
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@simas.test',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    });

    test('registration fails if email format is invalid', function () {
        $response = $this->post('/register', [
            'name' => 'Ahmad Fauzi',
            'email' => 'bukan-email-valid',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });
});
