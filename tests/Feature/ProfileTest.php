<?php

declare(strict_types=1);

use App\Models\LogAktivitas;
use App\Models\User;

beforeEach(function () {
    /** @var User $this->user */
    $this->user = User::factory()->create();
});

describe('User Profile', function () {

    describe('Page Rendering', function () {
        
        test('profile page is displayed', function () {
            $response = $this->actingAs($this->user)->get('/profile');
            $response->assertOk();
        });

        test('guest cannot access profile page', function () {
            $this->get('/profile')->assertRedirect(route('login'));
        });
    });

    describe('Profile Update', function () {
        
        test('profile information can be updated', function () {
            $response = $this
                ->actingAs($this->user)
                ->patch('/profile', [
                    'name' => 'Test User Baru',
                    'email' => 'test@example.com',
                ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect('/profile');

            $this->user->refresh();

            $this->assertSame('Test User Baru', $this->user->name);
            $this->assertSame('test@example.com', $this->user->email);
            
            $this->assertNull($this->user->email_verified_at);

            expect(LogAktivitas::where('aksi', 'update_profile')
                ->where('model_id', $this->user->id)
                ->exists())->toBeTrue();
        });

        test('email verification status is unchanged when the email address is unchanged', function () {
            $response = $this
                ->actingAs($this->user)
                ->patch('/profile', [
                    'name' => 'Test User Baru',
                    'email' => $this->user->email, 
                ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect('/profile');

            $this->assertNotNull($this->user->refresh()->email_verified_at);
        });

        test('email is sanitized to lowercase and trimmed during update', function () {
            $this->actingAs($this->user)
                ->patch('/profile', [
                    'name' => 'Test User',
                    'email' => 'TEST@EXAMPLE.COM  ', 
                ]);

            $this->user->refresh();
            
            $this->assertSame('test@example.com', $this->user->email);
        });
    });

    describe('Account Deletion', function () {
        
        test('user can delete their account', function () {
            $response = $this
                ->actingAs($this->user)
                ->delete('/profile', [
                    'password' => 'password',
                ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect('/');

            $this->assertGuest();
            $this->assertNull($this->user->fresh());

            expect(LogAktivitas::where('aksi', 'delete_own_account')
                ->where('model_id', $this->user->id)
                ->exists())->toBeTrue();
        });

        test('correct password must be provided to delete account', function () {
            $response = $this
                ->actingAs($this->user)
                ->from('/profile')
                ->delete('/profile', [
                    'password' => 'wrong-password',
                ]);

            $response
                ->assertSessionHasErrorsIn('userDeletion', 'password')
                ->assertRedirect('/profile');

            $this->assertNotNull($this->user->fresh());
        });
    });

    describe('Validation Rules', function () {
        
        test('name cannot exceed 255 characters', function () {
            $this->actingAs($this->user)
                ->patch('/profile', [
                    'name' => str_repeat('A', 256),
                    'email' => 'test@example.com',
                ])
                ->assertSessionHasErrors('name');
        });

        test('email must be a valid format', function () {
            $this->actingAs($this->user)
                ->patch('/profile', [
                    'name' => 'Test User',
                    'email' => 'bukan-email-valid',
                ])
                ->assertSessionHasErrors('email');
        });

        test('email must be unique (cannot use another users email)', function () {
            $otherUser = User::factory()->create(['email' => 'other@example.com']);

            $this->actingAs($this->user)
                ->patch('/profile', [
                    'name' => 'Test User',
                    'email' => 'other@example.com',
                ])
                ->assertSessionHasErrors('email');
        });
    });

});