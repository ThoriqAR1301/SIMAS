<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Log;

describe('Password Confirmation', function () {

    test('confirm password screen can be rendered', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/confirm-password');

        $response->assertStatus(200);
    });

    test('password can be confirmed successfully', function () {
        Log::spy();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        Log::shouldHaveReceived('info')
            ->withArgs(fn ($message) => str_contains($message, 'Konfirmasi password berhasil'))
            ->once();
    });

    test('password is not confirmed with invalid password', function () {
        Log::spy();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();

        Log::shouldHaveReceived('warning')
            ->withArgs(fn ($message) => str_contains($message, 'Percobaan konfirmasi password gagal'))
            ->once();
    });

    test('password confirmation requires a password', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    });

    test('user is redirected back to intended url after confirmation', function () {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/profile');

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect(route('profile.edit'));
    });
});
