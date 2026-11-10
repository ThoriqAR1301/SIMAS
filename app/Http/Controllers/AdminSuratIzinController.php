<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SuratIzin;
use App\Services\LogAktivitasService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AdminSuratIzinController extends Controller
{
    public function __construct(
        private readonly LogAktivitasService $logService
    ) {}

    public function index(Request $request): View
    {
        $query = SuratIzin::with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('jabatan'), fn ($q) => $q->where('jabatan', $request->jabatan))
            ->when($request->filled('q'), function ($q) use ($request) {
                $keyword = $request->input('q');
                $q->where(function ($w) use ($keyword) {
                    $w->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('alasan', 'like', "%{$keyword}%");
                });
            })
            ->latest();

        $surats = $query->paginate(15)->withQueryString();

        $hitung = [
            'pending' => SuratIzin::where('status', 'pending')->count(),
            'disetujui' => SuratIzin::where('status', 'disetujui')->count(),
            'ditolak' => SuratIzin::where('status', 'ditolak')->count(),
        ];

        return view('surat-izin.admin.index', compact('surats', 'hitung'));
    }

    public function show(SuratIzin $suratIzin): View
    {
        return view('surat-izin.admin.show', [
            'surat' => $suratIzin->load('user', 'pemroses'),
        ]);
    }

    public function setujui(Request $request, SuratIzin $suratIzin): RedirectResponse
    {
        if ($suratIzin->status !== 'pending') {
            return back()->with('error', 'Surat ini sudah diproses sebelumnya');
        }

        $data = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($suratIzin, $data) {
            $before = $suratIzin->toArray();

            $suratIzin->update([
                'status' => 'disetujui',
                'catatan_admin' => $data['catatan_admin'] ?? null,
                'diproses_oleh' => Auth::id(),
                'diproses_pada' => now(),
            ]);

            $this->logService->catat(
                'approve_surat_izin',
                $suratIzin,
                $before,
                $suratIzin->fresh()->toArray()
            );
        });

        return redirect()->route('admin.surat-izin.show', $suratIzin)
            ->with('success', 'Surat izin berhasil disetujui.');
    }

    public function tolak(Request $request, SuratIzin $suratIzin): RedirectResponse
    {
        $data = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ]);

        if ($suratIzin->status !== 'pending') {
            return back()->with('error', 'Surat ini sudah diproses sebelumnya');
        }

        DB::transaction(function () use ($suratIzin, $data) {
            $before = $suratIzin->toArray();

            $suratIzin->update([
                'status' => 'ditolak',
                'catatan_admin' => $data['catatan_admin'],
                'diproses_oleh' => Auth::id(),
                'diproses_pada' => now(),
            ]);

            $this->logService->catat(
                'reject_surat_izin',
                $suratIzin,
                $before,
                $suratIzin->fresh()->toArray()
            );
        });

        return redirect()->route('admin.surat-izin.show', $suratIzin)
            ->with('success', 'Surat izin telah ditolak');
    }

    public function pdf(Request $request, SuratIzin $suratIzin): Response
    {
        $pdf = Pdf::loadView('surat-izin.pdf', ['surat' => $suratIzin])
            ->setPaper('a4', 'portrait');

        $file = 'surat-izin-'.$suratIzin->id.'.pdf';

        return $request->has('download') ? $pdf->download($file) : $pdf->stream($file);
    }
}
