<?php

namespace App\Http\Controllers;

use App\Models\IzinNotifikasi;
use App\Models\SuratIzin;
use Illuminate\Http\Request;

class IzinNotifikasiController extends Controller
{
    private function milikSaya()
    {
        return IzinNotifikasi::where('user_id', auth()->id())->whereNull('dibaca_at');
    }

    public function index()
    {
        $notifs = $this->milikSaya()->latest()->get();
        return view('izin-notif.index', compact('notifs'));
    }

    public function jumlah()
    {
        return response()->json(['count' => $this->milikSaya()->count()]);
    }

    public function baca($notif)
    {
        $n = IzinNotifikasi::where('user_id', auth()->id())->findOrFail($notif);
        $n->update(['dibaca_at' => now()]);

        $surat = $n->surat_izin_id ? SuratIzin::find($n->surat_izin_id) : null;
        if (!$surat) {
            return redirect()->route('izin-notif.index');
        }

        return $surat->user_id === auth()->id()
            ? redirect()->route('surat-izin.show', $surat)
            : redirect()->route('admin.surat-izin.show', $surat);
    }

    public function bacaSemua()
    {
        $this->milikSaya()->update(['dibaca_at' => now()]);
        return back();
    }
}