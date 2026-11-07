<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $notifications = $this->authenticatedUser()->notifications()->latest()->paginate(20);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $notifications->map(fn ($notification) => [
                    'id' => $notification->id,
                    'judul' => $notification->data['judul'] ?? 'Notifikasi',
                    'pesan' => $notification->data['pesan'] ?? '',
                    'url' => $notification->data['url'] ?? '#',
                    'aksi' => $notification->data['aksi'] ?? 'submitted',
                    'waktu' => $notification->created_at->diffForHumans(),
                    'read_at' => $notification->read_at?->toIso8601String(),
                ]),
                'total' => $notifications->total(),
            ]);
        }

        return view('notifikasi.index', compact('notifications'));
    }

    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'count' => $this->authenticatedUser()->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead(string $id): JsonResponse
    {
        $notification = $this->authenticatedUser()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        $this->authenticatedUser()->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }
}
