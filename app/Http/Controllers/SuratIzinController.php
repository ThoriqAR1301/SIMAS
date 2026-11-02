<?php

namespace App\Http\Controllers;

use App\Models\SuratIzin;
use App\Support\Peran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratIzinController extends Controller
{
    private function authorizeView(SuratIzin $surat): void
    {
        abort_unless(Peran::isAdmin() || $surat->user_id === auth()->id(), 403);
    }

    private function authorizeEdit(SuratIzin $surat): void
    {
        abort_unless($surat->user_id === auth()->id(), 403);
        abort_unless($surat->status === 'pending', 403, 'Surat yang sudah diproses tidak bisa diedit.');
    }

    private function rules(): array
    {
        return [
            'tanggal_izin' => 'required|date',
            'alasan'       => 'required|string|max:500',
            'tanda_tangan' => 'nullable|string',
            'hapus_ttd'    => 'nullable|boolean',
        ];
    }

    private function identitas(): array
    {
        return [
            'nama'    => auth()->user()->name,
            'jabatan' => Peran::data()['label'],
        ];
    }

    public function index()
    {
        $surats = SuratIzin::where('user_id', auth()->id())->latest()->paginate(10);
        return view('surat-izin.index', compact('surats'));
    }

    public function create()
    {
        return view('surat-izin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        unset($data['hapus_ttd']);

        $data += $this->identitas();
        $data['user_id']       = auth()->id();
        $data['tanggal_surat'] = now()->toDateString();
        $data['status']        = 'pending';

        $surat = SuratIzin::create($data); // notifikasi ke admin dikirim otomatis dari model

        return redirect()->route('surat-izin.show', $surat)
            ->with('success', 'Surat izin berhasil dibuat.');
    }

    public function show(SuratIzin $suratIzin)
    {
        $this->authorizeView($suratIzin);
        return view('surat-izin.show', ['surat' => $suratIzin]);
    }

    public function edit(SuratIzin $suratIzin)
    {
        $this->authorizeEdit($suratIzin);
        return view('surat-izin.edit', ['surat' => $suratIzin]);
    }

    public function update(Request $request, SuratIzin $suratIzin)
    {
        $this->authorizeEdit($suratIzin);

        $data = $request->validate($this->rules());
        $hapus = !empty($data['hapus_ttd']);
        unset($data['hapus_ttd']);

        if (empty($data['tanda_tangan'])) {
            if ($hapus) {
                $data['tanda_tangan'] = null;
            } else {
                unset($data['tanda_tangan']);
            }
        }

        $data += $this->identitas();
        $suratIzin->update($data);

        return redirect()->route('surat-izin.show', $suratIzin)
            ->with('success', 'Surat izin berhasil diperbarui.');
    }

    public function pdf(Request $request, SuratIzin $suratIzin)
    {
        $this->authorizeView($suratIzin);

        $pdf = Pdf::loadView('surat-izin.pdf', ['surat' => $suratIzin])
            ->setPaper('a4', 'portrait');

        $file = 'surat-izin-' . $suratIzin->id . '.pdf';

        return $request->has('download') ? $pdf->download($file) : $pdf->stream($file);
    }
}