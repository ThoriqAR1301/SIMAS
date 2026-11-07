<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuratPerizinanSakitRequest;
use App\Models\SuratPerizinanSakit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SuratPerizinanSakitController extends Controller
{
    /**
     * Menampilkan daftar pengajuan izin sakit.
     * Guru hanya melihat pengajuannya sendiri.
     * Admin TU dapat melihat seluruh pengajuan.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdminTu()) {
            $pengajuan = SuratPerizinanSakit::with('user')
                ->latest()
                ->get();

            return view('surat-perizinan-sakit.tu.index', [
                'pengajuan' => $pengajuan,
            ]);
        }

        abort_unless($user->isGuru(), 403);

        $pengajuan = SuratPerizinanSakit::whereBelongsTo($user)
            ->latest()
            ->get();

        return view('surat-perizinan-sakit.index', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Menampilkan formulir pengajuan izin sakit.
     */
    public function create(): View
    {
        abort_unless(auth()->user()->isGuru(), 403);

        return view('surat-perizinan-sakit.create');
    }

    /**
     * Menyimpan pengajuan izin sakit baru dari Guru.
     */
    public function store(
        StoreSuratPerizinanSakitRequest $request
    ): RedirectResponse {
        abort_unless($request->user()->isGuru(), 403);

        $path = $request->file('surat_dokter')
            ->store('surat-dokter', 'local');

        try {
            $surat = new SuratPerizinanSakit(
                $request->safe()->only([
                    'tanggal_mulai',
                    'tanggal_selesai',
                    'alasan',
                ])
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
            ->with(
                'success',
                'Pengajuan izin sakit berhasil dikirim kepada Tata Usaha dan sedang menunggu pemeriksaan.'
            );
    }

    /**
     * Menampilkan detail pengajuan.
     */
    public function show(
        Request $request,
        SuratPerizinanSakit $suratPerizinanSakit
    ): View {
        $user = $request->user();

        abort_unless(
            $user->isAdminTu() ||
            (
                $user->isGuru() &&
                $suratPerizinanSakit->user_id === $user->id
            ),
            404
        );

        if ($user->isAdminTu()) {
            return view('surat-perizinan-sakit.tu.show', [
                'pengajuan' => $suratPerizinanSakit,
            ]);
        }

        return view('surat-perizinan-sakit.show', [
            'pengajuan' => $suratPerizinanSakit,
        ]);
    }

    /**
     * Memproses keputusan pemeriksaan oleh Admin TU.
     */
    public function verify(
        Request $request,
        SuratPerizinanSakit $suratPerizinanSakit
    ): RedirectResponse {
        abort_unless($request->user()->isAdminTu(), 403);

        $validated = $request->validate([
            'keputusan' => [
                'required',
                'in:disetujui,ditolak,perlu_revisi',
            ],
            'catatan_tu' => [
                'nullable',
                'required_if:keputusan,perlu_revisi,ditolak',
                'string',
                'max:500',
            ],
        ]);

        $suratPerizinanSakit->status = $validated['keputusan'];

        if (array_key_exists('catatan_tu', $validated)) {
            $suratPerizinanSakit->catatan_tu = $validated['catatan_tu'];
        }

        $suratPerizinanSakit->save();

        return redirect()
            ->route('surat-perizinan-sakit.show', $suratPerizinanSakit)
            ->with(
                'status',
                'Keputusan pemeriksaan berhasil disimpan.'
            );
    }

    /**
     * Memproses revisi pengajuan dari Guru.
     */
    public function revisi(
        Request $request,
        SuratPerizinanSakit $suratPerizinanSakit
    ): RedirectResponse {
        $user = $request->user();

        abort_unless($user->isGuru(), 403);

        abort_unless(
            $suratPerizinanSakit->user_id === $user->id,
            404
        );

        abort_unless(
            $suratPerizinanSakit->status ===
                SuratPerizinanSakit::STATUS_PERLU_REVISI,
            403
        );

        $validated = $request->validate([
            'tanggal_mulai' => [
                'required',
                'date',
            ],
            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'alasan' => [
                'required',
                'string',
                'max:2000',
            ],
            'surat_dokter' => [
                'sometimes',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $oldPath = $suratPerizinanSakit->surat_dokter_path;
        $newPath = null;

        // Simpan dokumen baru terlebih dahulu jika Guru menggantinya.
        if ($request->hasFile('surat_dokter')) {
            $newPath = $request->file('surat_dokter')
                ->store('surat-dokter', 'local');

            if (!$newPath) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'surat_dokter' => 'Dokumen pengganti gagal disimpan. Silakan coba kembali.',
                    ]);
            }
        }

        try {
            $suratPerizinanSakit->tanggal_mulai =
                $validated['tanggal_mulai'];

            $suratPerizinanSakit->tanggal_selesai =
                $validated['tanggal_selesai'];

            $suratPerizinanSakit->alasan =
                $validated['alasan'];

            if ($newPath) {
                $suratPerizinanSakit->surat_dokter_path = $newPath;
            }

            // Setelah revisi dikirim, pengajuan kembali menunggu pemeriksaan.
            $suratPerizinanSakit->status =
                SuratPerizinanSakit::STATUS_MENUNGGU;

            $suratPerizinanSakit->save();
        } catch (Throwable $exception) {
            // Hapus file baru jika penyimpanan data gagal.
            if ($newPath) {
                Storage::disk('local')->delete($newPath);
            }

            throw $exception;
        }

        // Hapus file lama hanya setelah data revisi berhasil disimpan.
        if ($newPath && $oldPath) {
            Storage::disk('local')->delete($oldPath);
        }

        return redirect()
            ->route('surat-perizinan-sakit.show', $suratPerizinanSakit)
            ->with(
                'success',
                'Perbaikan pengajuan berhasil dikirim kembali kepada Tata Usaha.'
            );
    }

    /**
     * Menampilkan atau mengunduh dokumen surat dokter.
     */
    public function dokter(
        Request $request,
        SuratPerizinanSakit $suratPerizinanSakit
    ): Response {
        $user = $request->user();

        abort_unless(
            $user->isAdminTu() ||
            (
                $user->isGuru() &&
                $suratPerizinanSakit->user_id === $user->id
            ),
            403
        );

        $relativePath = $suratPerizinanSakit->surat_dokter_path;

        abort_if(empty($relativePath), 404);

        $disk = Storage::disk('local');

        abort_unless($disk->exists($relativePath), 404);

        $extension = strtolower(
            pathinfo($relativePath, PATHINFO_EXTENSION)
        );

        $mime = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            default => abort(404),
        };

        $path = $disk->path($relativePath);

        $headers = [
            'Content-Type' => $mime,
            'X-Content-Type-Options' => 'nosniff',
            'Content-Disposition' => $request->boolean('download')
                ? 'attachment'
                : 'inline',
        ];

        if ($request->boolean('download')) {
            return response()->download(
                $path,
                basename($relativePath),
                $headers
            );
        }

        return response()->file($path, $headers);
    }
}