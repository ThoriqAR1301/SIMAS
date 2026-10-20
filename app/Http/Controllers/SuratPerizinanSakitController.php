<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratPerizinanSakitRequest;
use App\Models\SuratPerizinanSakit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SuratPerizinanSakitController extends Controller
{
    /**
     * Show the sick leave letters submitted by the authenticated user.
     */
    public function index(Request $request): View
    {
        $pengajuan = SuratPerizinanSakit::query()
            ->whereBelongsTo($request->user())
            ->latest()
            ->get();

        return view('surat-perizinan-sakit.index', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show the form for submitting a sick leave letter.
     */
    public function create(): View
    {
        return view('surat-perizinan-sakit.create');
    }

    /**
     * Store a newly submitted sick leave letter.
     */
    public function store(StoreSuratPerizinanSakitRequest $request): RedirectResponse
    {
        $path = $request->file('surat_dokter')->store('surat-dokter', 'local');

        try {
            $surat = new SuratPerizinanSakit(
                $request->safe()->only(['tanggal_mulai', 'tanggal_selesai', 'alasan'])
            );
            $surat->user_id = $request->user()->id;
            $surat->surat_dokter_path = $path;
            $surat->status = SuratPerizinanSakit::STATUS_MENUNGGU;
            $surat->save();
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);

            throw $exception;
        }

        return redirect()
            ->route('surat-perizinan-sakit.create')
            ->with('status', 'Pengajuan izin sakit berhasil dikirim dan menunggu persetujuan Tata Usaha.');
    }

    /**
     * Show a single sick leave letter owned by the authenticated user.
     */
    public function show(Request $request, SuratPerizinanSakit $suratPerizinanSakit): View
    {
        abort_unless($suratPerizinanSakit->user_id === $request->user()->id, 404);

        return view('surat-perizinan-sakit.show', [
            'pengajuan' => $suratPerizinanSakit,
        ]);
    }
}
