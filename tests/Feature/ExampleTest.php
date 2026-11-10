<?php

declare(strict_types=1);

use App\Models\User;

describe('Landing Page', function () {

    test('returns a successful response and renders key content', function () {
        $response = $this->get('/');

        $response->assertStatus(200);
        
        $response->assertSee('SIMAS');
        $response->assertSee('Temukan');
        $response->assertSee('Efisiensi');
        
        $response->assertSee('<title>SIMAS', false);
    });

    test('shows login and register buttons for guests', function () {
        $response = $this->get('/');

        $response->assertSee('Masuk');
        $response->assertSee('Daftar');
        
        $response->assertDontSee('Dashboard');
        
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
    });

    test('shows dashboard button for authenticated users', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertSee('Dashboard');
        
        $response->assertSee(route('dashboard'));
        
    });

    test('redirects unauthenticated users trying to access dashboard to login', function () {
        $response = $this->get('/dashboard');
        
        $response->assertRedirect(route('login'));
    });

});