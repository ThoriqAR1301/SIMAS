<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSuratMasukRequest;
use App\Models\SuratMasuk;
use App\Services\LogAktivitasService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratMasukController extends Controller
{
    public function __construct(
        private readonly LogAktivitasService $logService
    ) {}

    public function index(Request $request): View
    {
        $search = $request->input('search');
        $folder = $request->input('folder');

        $suratMasuks = SuratMasuk::with('user')
            ->when($search, fn ($q) => $q->search($search))
            ->when($folder, fn ($q) => $q->byKategori($folder))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $folders = SuratMasuk::select('kategori_folder')
            ->distinct()
            ->pluck('kategori_folder');

        return view('surat-masuk.index', compact('suratMasuks', 'folders', 'search', 'folder'));
    }

    public function create(): View
    {
        $randomCode = 'SM-'.strtoupper(Str::random(6));

        return view('surat-masuk.create', compact('randomCode'));
    }

    public function store(Request $request): RedirectResponse
    {
        $surat = DB::transaction(function () use ($request) {
            $filePath = null;

            if ($request->hasFile('file_surat')) {
                $filePath = $request->file('file_surat')->store('surat_masuk', 'public');
            }

            $surat = SuratMasuk::create([
                'random_code' => $request->random_code,
                'nama_surat' => $request->nama_surat,
                'kategori_folder' => $request->kategori_folder,
                'asal_surat' => $request->asal_surat,
                'tanggal_diterima' => $request->tanggal_diterima,
                'deskripsi' => $request->deskripsi,
                'file_surat' => $filePath,
                'user_id' => $request->user()->id,
            ]);

            $this->logService->catat('create_surat_masuk', $surat, null, $surat->toArray());

            return $surat;
        });

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat Masuk berhasil diinput!');
    }

    public function show(SuratMasuk $suratMasuk): View
    {
        return view('surat-masuk.show', compact('suratMasuk'));
    }

    public function edit(SuratMasuk $suratMasuk): View
    {
        return view('surat-masuk.edit', compact('suratMasuk'));
    }

    public function update(UpdateSuratMasukRequest $request, SuratMasuk $suratMasuk): RedirectResponse
    {
        DB::transaction(function () use ($request, $suratMasuk) {
            $before = $suratMasuk->toArray();
            $filePath = $suratMasuk->file_surat;

            if ($request->hasFile('file_surat')) {
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                $filePath = $request->file('file_surat')->store('surat_masuk', 'public');
            }

            $suratMasuk->update([
                'nama_surat' => $request->nama_surat,
                'kategori_folder' => $request->kategori_folder,
                'asal_surat' => $request->asal_surat,
                'tanggal_diterima' => $request->tanggal_diterima,
                'deskripsi' => $request->deskripsi,
                'file_surat' => $filePath,
            ]);

            $this->logService->catat('update_surat_masuk', $suratMasuk, $before, $suratMasuk->fresh()->toArray());
        });

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat Masuk berhasil diperbarui!');
    }

    public function destroy(SuratMasuk $suratMasuk): RedirectResponse
    {
        DB::transaction(function () use ($suratMasuk) {
            $this->logService->catat('delete_surat_masuk', $suratMasuk, $suratMasuk->toArray(), null);

            if ($suratMasuk->file_surat && Storage::disk('public')->exists($suratMasuk->file_surat)) {
                Storage::disk('public')->delete($suratMasuk->file_surat);
            }

            $suratMasuk->delete();
        });

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat Masuk berhasil dihapus!');
    }
}
