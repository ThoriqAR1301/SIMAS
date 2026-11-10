<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function () {
    RateLimiter::clear('forgot-password:127.0.0.1');
});

describe('Password Reset', function () {

    test('reset password link screen can be rendered', function () {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    });

    test('reset password link can be requested', function () {
        Notification::fake();
        Log::spy();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => str_contains($message, 'Permintaan tautan reset password berhasil'))
            ->once();
    });

    test('reset password screen can be rendered', function () {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/'.$notification->token);
            $response->assertStatus(200);

            return true;
        });
    });

    test('password can be reset with valid token', function () {
        Notification::fake();
        Log::spy();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            Log::shouldHaveReceived('info')
                ->withArgs(fn ($message) => str_contains($message, 'Password berhasil diubah'))
                ->once();

            return true;
        });
    });

    test('email input is sanitized on forgot password', function () {
        Notification::fake();
        $user = User::factory()->create(['email' => 'admin@simas.test']);

        $this->post('/forgot-password', ['email' => '  ADMIN@simas.test  ']);

        Notification::assertSentTo($user, ResetPassword::class);
    });

    test('forgot password is rate limited after 3 attempts', function () {
        Notification::fake();
        $user = User::factory()->create();

        foreach (range(1, 3) as $i) {
            $this->post('/forgot-password', ['email' => $user->email]);
        }

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertSessionHasErrors('email');

        $errors = session('errors')->get('email');
        expect($errors[0])->toContain('Terlalu banyak');
    });

    test('failed forgot password request is logged', function () {
        Log::spy();

        $this->post('/forgot-password', ['email' => 'tidak-ada@simas.test']);

        Log::shouldHaveReceived('warning')
            ->withArgs(fn ($message) => str_contains($message, 'Permintaan tautan reset password gagal'))
            ->once();
    });
});
