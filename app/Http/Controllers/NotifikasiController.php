<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotifikasiController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $notifications->map(fn (DatabaseNotification $n) => $this->formatNotification($n)),
                'total' => $notifications->total(),
            ]);
        }

        return view('notifikasi.index', compact('notifications'));
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $count = $request->user()->unreadNotifications()->count();

        return response()->json([
            'status' => 'success',
            'count' => $count,
        ]);
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi berhasil ditandai sebagai dibaca',
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Semua notifikasi berhasil ditandai sebagai dibaca',
        ]);
    }

    private function formatNotification(DatabaseNotification $notification): array
    {
        return [
            'id' => $notification->id,
            'judul' => $notification->data['judul'] ?? 'Notifikasi',
            'pesan' => $notification->data['pesan'] ?? '',
            'url' => $notification->data['url'] ?? '#',
            'aksi' => $notification->data['aksi'] ?? 'submitted',
            'waktu' => $notification->created_at->diffForHumans(),
            'read_at' => $notification->read_at?->toIso8601String(),
        ];
    }
}
