<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function () {
    RateLimiter::clear('test@example.com|127.0.0.1');
});

describe('Authentication', function () {

    test('login screen can be rendered', function () {
        $response = $this->get('/login');
        $response->assertStatus(200);
    });

    test('users can authenticate using the login screen', function () {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    });

    test('users can not authenticate with invalid password', function () {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    });

    test('users can logout', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    });

    test('email input is automatically sanitized (lowercase & trim)', function () {
        $user = User::factory()->create([
            'email' => 'ahmad@simas.test',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => '  AHMAD@simas.test  ',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    });

    test('inactive users cannot authenticate', function () {
        $user = User::factory()->create([
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');

        $errors = session('errors')->get('email');
        expect($errors[0])->toContain('dinonaktifkan');
    });

    test('login is rate limited after 5 failed attempts', function () {
        $user = User::factory()->create();

        foreach (range(1, 5) as $i) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');

        $errors = session('errors')->get('email');
        expect($errors[0])->toContain('Terlalu banyak');
    });
});
