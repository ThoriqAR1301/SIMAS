<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\IzinNotifikasi;
use App\Models\SuratIzin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IzinNotifikasiController extends Controller
{
    private function unreadNotifications(Request $request): Builder
    {
        return IzinNotifikasi::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('dibaca_at');
    }

    public function index(Request $request): View
    {
        $notifs = $this->unreadNotifications($request)
            ->latest()
            ->get();

        return view('izin-notif.index', compact('notifs'));
    }

    public function jumlah(Request $request): JsonResponse
    {
        $count = $this->unreadNotifications($request)->count();

        return response()->json(['count' => $count]);
    }

    public function baca(Request $request, string $notif): RedirectResponse
    {
        $user = $request->user();

        $n = IzinNotifikasi::where('user_id', $user->id)->findOrFail($notif);

        $n->update(['dibaca_at' => now()]);

        $surat = $n->surat_izin_id ? SuratIzin::find($n->surat_izin_id) : null;

        if (! $surat) {
            return redirect()->route('izin-notif.index');
        }

        if ($surat->user_id === $user->id) {
            return redirect()->route('surat-izin.show', $surat);
        }

        return redirect()->route('admin.surat-izin.show', $surat);
    }

    public function bacaSemua(Request $request): RedirectResponse
    {
        $this->unreadNotifications($request)->update(['dibaca_at' => now()]);

        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca');
    }
}
