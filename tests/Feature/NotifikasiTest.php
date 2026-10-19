<?php

use App\Models\User;
use Illuminate\Support\Str;

it('renders authenticated pages that include the notification bell', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});

it('returns the authenticated users unread notification count', function () {
    $user = User::factory()->create();

    $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'test',
        'data' => ['judul' => 'Pengajuan baru'],
    ]);

    $response = $this->actingAs($user)
        ->getJson(route('notifikasi.unread-count'));

    $response->assertOk()
        ->assertJsonPath('count', 1);
});

it('marks one of the authenticated users notifications as read', function () {
    $user = User::factory()->create();
    $notification = $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'test',
        'data' => ['judul' => 'Pengajuan baru'],
    ]);

    $response = $this->actingAs($user)
        ->postJson(route('notifikasi.baca', $notification->id));

    $response->assertOk()
        ->assertExactJson(['ok' => true]);
    expect($notification->fresh()->read_at)->not->toBeNull();
});

it('does not mark another users notification as read', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $notification = $owner->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'test',
        'data' => ['judul' => 'Pengajuan baru'],
    ]);

    $this->actingAs($otherUser)
        ->postJson(route('notifikasi.baca', $notification->id))
        ->assertNotFound();

    expect($notification->fresh()->read_at)->toBeNull();
});

it('marks all of the authenticated users unread notifications as read', function () {
    $user = User::factory()->create();
    $firstNotification = $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'test',
        'data' => ['judul' => 'Pengajuan pertama'],
    ]);
    $secondNotification = $user->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'test',
        'data' => ['judul' => 'Pengajuan kedua'],
    ]);

    $this->actingAs($user)
        ->postJson(route('notifikasi.baca-semua'))
        ->assertOk()
        ->assertExactJson(['ok' => true]);

    expect($firstNotification->fresh()->read_at)->not->toBeNull();
    expect($secondNotification->fresh()->read_at)->not->toBeNull();
});
