<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    /** @var User $this->user */
    $this->user = User::factory()->create();
});

describe('Notification System', function () {

    describe('UI & Middleware', function () {
        
        test('renders authenticated pages with notification bell', function () {
            $this->actingAs($this->user)
                ->get(route('dashboard'))
                ->assertOk();
        });

        test('guests cannot access notification endpoints', function () {
            $this->getJson(route('notifikasi.unread-count'))->assertUnauthorized();
            
            $this->postJson(route('notifikasi.baca-semua'))->assertUnauthorized();
        });
    });

    describe('API Endpoints', function () {
        
        test('returns unread count as 0 when there are no notifications', function () {
            $response = $this->actingAs($this->user)->getJson(route('notifikasi.unread-count'));
            
            $response->assertOk()
                ->assertJsonPath('status', 'success')
                ->assertJsonPath('count', 0);
        });

        test('returns the correct unread count', function () {
            $this->user->notifications()->create([
                'id' => (string) Str::uuid(),
                'type' => 'test',
                'data' => ['judul' => 'Notif 1'],
            ]);
            $this->user->notifications()->create([
                'id' => (string) Str::uuid(),
                'type' => 'test',
                'data' => ['judul' => 'Notif 2'],
            ]);

            $response = $this->actingAs($this->user)->getJson(route('notifikasi.unread-count'));
            
            $response->assertOk()
                ->assertJsonPath('status', 'success')
                ->assertJsonPath('count', 2);
        });

        test('returns formatted notifications list for dropdown', function () {
            $this->user->notifications()->create([
                'id' => (string) Str::uuid(),
                'type' => 'test',
                'data' => [
                    'judul' => 'Pengajuan Disetujui',
                    'pesan' => 'Surat tugas Anda telah disetujui',
                    'url' => '/pengajuan/1',
                    'aksi' => 'approved'
                ],
            ]);

            $response = $this->actingAs($this->user)->getJson(route('notifikasi.index'));

            $response->assertOk()
                ->assertJsonPath('status', 'success')
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.judul', 'Pengajuan Disetujui')
                ->assertJsonPath('data.0.aksi', 'approved')
                ->assertJsonPath('data.0.read_at', null);
        });
    });

    describe('Read Actions', function () {
        
        test('marks a single notification as read', function () {
            $notification = $this->user->notifications()->create([
                'id' => (string) Str::uuid(),
                'type' => 'test',
                'data' => ['judul' => 'Pengajuan Baru'],
            ]);

            $response = $this->actingAs($this->user)
                ->postJson(route('notifikasi.baca', $notification->id));

            $response->assertOk()
                ->assertExactJson([
                    'status' => 'success',
                    'message' => 'Notifikasi berhasil ditandai sebagai dibaca',
                ]);

            expect($notification->fresh()->read_at)->not->toBeNull();
        });

        test('marks all notifications as read', function () {
            $this->user->notifications()->createMany([
                ['id' => (string) Str::uuid(), 'type' => 'test', 'data' => ['judul' => 'Notif 1']],
                ['id' => (string) Str::uuid(), 'type' => 'test', 'data' => ['judul' => 'Notif 2']],
            ]);

            $response = $this->actingAs($this->user)
                ->postJson(route('notifikasi.baca-semua'));

            $response->assertOk()
                ->assertExactJson([
                    'status' => 'success',
                    'message' => 'Semua notifikasi berhasil ditandai sebagai dibaca',
                ]);

            expect($this->user->unreadNotifications()->count())->toBe(0);
        });
    });

    describe('Security & IDOR Protection', function () {
        
        test('does not allow reading another users notification', function () {
            $owner = User::factory()->create();
            $otherUser = User::factory()->create();

            $notification = $owner->notifications()->create([
                'id' => (string) Str::uuid(),
                'type' => 'test',
                'data' => ['judul' => 'Rahasia'],
            ]);

            $this->actingAs($otherUser)
                ->postJson(route('notifikasi.baca', $notification->id))
                ->assertNotFound(); 

            expect($notification->fresh()->read_at)->toBeNull();
        });
    });

});