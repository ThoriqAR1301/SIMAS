<?php

declare(strict_types=1);

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

describe('Email Verification', function () {

    test('email verification screen can be rendered', function () {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    });

    test('email can be verified with valid hash', function () {
        $user = User::factory()->unverified()->create();
        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        expect($user->fresh()->hasVerifiedEmail())->toBeTrue();

        $response->assertRedirect(route('dashboard', absolute: false));
        $response->assertSessionHas('success');

        expect(LogAktivitas::where('aksi', 'verify_email')
            ->where('model_id', $user->id)
            ->exists())->toBeTrue();
    });

    test('email is not verified with invalid hash', function () {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
    });

    test('email is not verified with expired link', function () {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinutes(1),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($verificationUrl);

        expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
    });

    test('user can resend verification email', function () {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->post('/email/verification-notification');

        $response->assertSessionHas('status');
    });

    test('resending verification email is rate limited', function () {
        $user = User::factory()->unverified()->create();

        foreach (range(1, 3) as $i) {
            $this->actingAs($user)->post('/email/verification-notification');
        }

        $response = $this->actingAs($user)->post('/email/verification-notification');

        $response->assertSessionHasErrors('email');
    });

    test('verified user is redirected to dashboard when trying to resend', function () {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->post('/email/verification-notification');

        $response->assertRedirect(route('dashboard', absolute: false));
    });
});
