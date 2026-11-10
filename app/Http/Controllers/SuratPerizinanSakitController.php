<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RevisiSuratPerizinanSakitRequest;
use App\Http\Requests\StoreSuratPerizinanSakitRequest;
use App\Http\Requests\VerifySuratPerizinanSakitRequest;
use App\Models\SuratPerizinanSakit;
use App\Services\LogAktivitasService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SuratPerizinanSakitController extends Controller
{
    public function __construct(
        private readonly LogAktivitasService $logService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdminTu()) {
            $pengajuan = SuratPerizinanSakit::with('user')->latest()->get();

            return view('surat-perizinan-sakit.tu.index', compact('pengajuan'));
        }

        abort_unless($user->isGuru(), 403);

        $pengajuan = SuratPerizinanSakit::whereBelongsTo($user)->latest()->get();

        return view('surat-perizinan-sakit.index', compact('pengajuan'));
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->isGuru(), 403);

        return view('surat-perizinan-sakit.create');
    }

    public function store(StoreSuratPerizinanSakitRequest $request): RedirectResponse
    {
        $path = $request->file('surat_dokter')->store('surat-dokter', 'local');

        if (! $path) {
            return back()->withInput()->withErrors([
                'surat_dokter' => 'Surat dokter gagal disimpan. Silakan coba kembali',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $path) {
                $surat = new SuratPerizinanSakit(
                    $request->safe()->only(['tanggal_mulai', 'tanggal_selesai', 'alasan'])
                );

                $surat->user_id = $request->user()->id;
                $surat->surat_dokter_path = $path;
                $surat->status = SuratPerizinanSakit::STATUS_MENUNGGU;
                $surat->save();

                $this->logService->catat('create_izin_sakit', $surat, null, $surat->toArray());
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        return $this->successResponse(
            'Pengajuan izin sakit berhasil dikirim kepada Tata Usaha dan sedang menunggu pemeriksaan',
            'surat-perizinan-sakit.create'
        );
    }

    public function show(Request $request, SuratPerizinanSakit $suratPerizinanSakit): View
    {
        $this->authorizeAccess($request, $suratPerizinanSakit);

        if ($request->user()->isAdminTu()) {
            return view('surat-perizinan-sakit.tu.show', ['pengajuan' => $suratPerizinanSakit]);
        }

        return view('surat-perizinan-sakit.show', ['pengajuan' => $suratPerizinanSakit]);
    }

    public function verify(VerifySuratPerizinanSakitRequest $request, SuratPerizinanSakit $suratPerizinanSakit): RedirectResponse
    {
        abort_unless(
            $suratPerizinanSakit->status === SuratPerizinanSakit::STATUS_MENUNGGU,
            403,
            'Pengajuan ini sudah diperiksa'
        );

        $validated = $request->validated();

        DB::transaction(function () use ($suratPerizinanSakit, $validated) {
            $before = $suratPerizinanSakit->toArray();

            $suratPerizinanSakit->status = $validated['keputusan'];
            $suratPerizinanSakit->catatan_tu = $validated['catatan_tu'] ?? null;
            $suratPerizinanSakit->save();

            $this->logService->catat('verify_izin_sakit', $suratPerizinanSakit, $before, $suratPerizinanSakit->fresh()->toArray());
        });

        return $this->successResponse(
            'Keputusan pemeriksaan berhasil disimpan',
            'surat-perizinan-sakit.show',
            [$suratPerizinanSakit]
        );
    }

    public function revisi(RevisiSuratPerizinanSakitRequest $request, SuratPerizinanSakit $suratPerizinanSakit): RedirectResponse
    {
        $user = $request->user();

        abort_unless($suratPerizinanSakit->user_id === $user->id, 404);
        abort_unless($suratPerizinanSakit->status === SuratPerizinanSakit::STATUS_PERLU_REVISI, 403);

        $validated = $request->validated();
        $oldPath = $suratPerizinanSakit->surat_dokter_path;
        $newPath = null;

        if ($request->hasFile('surat_dokter')) {
            $newPath = $request->file('surat_dokter')->store('surat-dokter', 'local');
            if (! $newPath) {
                return back()->withInput()->withErrors([
                    'surat_dokter' => 'Dokumen pengganti gagal disimpan. Silakan coba kembali',
                ]);
            }
        }

        try {
            DB::transaction(function () use ($suratPerizinanSakit, $validated, $newPath) {
                $before = $suratPerizinanSakit->toArray();

                $suratPerizinanSakit->tanggal_mulai = $validated['tanggal_mulai'];
                $suratPerizinanSakit->tanggal_selesai = $validated['tanggal_selesai'];
                $suratPerizinanSakit->alasan = $validated['alasan'];

                if ($newPath) {
                    $suratPerizinanSakit->surat_dokter_path = $newPath;
                }

                $suratPerizinanSakit->status = SuratPerizinanSakit::STATUS_MENUNGGU;
                $suratPerizinanSakit->catatan_tu = null;
                $suratPerizinanSakit->save();

                $this->logService->catat('revisi_izin_sakit', $suratPerizinanSakit, $before, $suratPerizinanSakit->fresh()->toArray());
            });
        } catch (Throwable $exception) {
            if ($newPath) {
                Storage::disk('local')->delete($newPath);
            }
            throw $exception;
        }

        if ($newPath && $oldPath) {
            Storage::disk('local')->delete($oldPath);
        }

        return $this->successResponse(
            'Perbaikan pengajuan berhasil dikirim kembali kepada Tata Usaha',
            'surat-perizinan-sakit.show',
            [$suratPerizinanSakit]
        );
    }

    public function dokter(Request $request, SuratPerizinanSakit $suratPerizinanSakit): Response
    {
        $this->authorizeAccess($request, $suratPerizinanSakit);

        $relativePath = $suratPerizinanSakit->surat_dokter_path;
        abort_if(empty($relativePath), 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($relativePath), 404);

        $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));

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
            'Content-Disposition' => $request->boolean('download') ? 'attachment' : 'inline',
        ];

        if ($request->boolean('download')) {
            return response()->download($path, basename($relativePath), $headers);
        }

        return response()->file($path, $headers);
    }

    private function authorizeAccess(Request $request, SuratPerizinanSakit $surat): void
    {
        $user = $request->user();

        abort_unless(
            $user->isAdminTu() || ($user->isGuru() && $surat->user_id === $user->id),
            404
        );
    }
}
