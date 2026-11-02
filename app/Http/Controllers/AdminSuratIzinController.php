<?php

namespace App\Http\Controllers;

use App\Models\SuratIzin;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminSuratIzinController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratIzin::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('alasan', 'like', "%{$q}%");
            });
        }

        $surats = $query->paginate(15)->withQueryString();

        $hitung = [
            'pending'   => SuratIzin::where('status', 'pending')->count(),
            'disetujui' => SuratIzin::where('status', 'disetujui')->count(),
            'ditolak'   => SuratIzin::where('status', 'ditolak')->count(),
        ];

        return view('surat-izin.admin.index', compact('surats', 'hitung'));
    }

    public function show(SuratIzin $suratIzin)
    {
        return view('surat-izin.admin.show', ['surat' => $suratIzin->load('user', 'pemroses')]);
    }

    public function setujui(Request $request, SuratIzin $suratIzin)
    {
        if ($suratIzin->status !== 'pending') {
            return back()->with('success', 'Surat ini sudah diproses sebelumnya.');
        }

        $suratIzin->update([
            'status'        => 'disetujui',
            'catatan_admin' => $request->input('catatan_admin'),
            'diproses_oleh' => auth()->id(),
            'diproses_pada' => now(),
        ]);

        return redirect()->route('admin.surat-izin.show', $suratIzin)
            ->with('success', 'Surat izin disetujui.');
    }

    public function tolak(Request $request, SuratIzin $suratIzin)
    {
        $request->validate(['catatan_admin' => 'required|string|max:500']);

        if ($suratIzin->status !== 'pending') {
            return back()->with('success', 'Surat ini sudah diproses sebelumnya.');
        }

        $suratIzin->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
            'diproses_oleh' => auth()->id(),
            'diproses_pada' => now(),
        ]);

        return redirect()->route('admin.surat-izin.show', $suratIzin)
            ->with('success', 'Surat izin ditolak.');
    }

    public function pdf(Request $request, SuratIzin $suratIzin)
    {
        $pdf = Pdf::loadView('surat-izin.pdf', ['surat' => $suratIzin])
            ->setPaper('a4', 'portrait');

        $file = 'surat-izin-' . $suratIzin->id . '.pdf';

        return $request->has('download') ? $pdf->download($file) : $pdf->stream($file);
    }
}