<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SuratIzin;
use App\Services\LogAktivitasService;
use App\Support\Peran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SuratIzinController extends Controller
{
    public function __construct(
        private readonly LogAktivitasService $logService
    ) {}

    public function index(Request $request): View
    {
        $surats = SuratIzin::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('surat-izin.index', compact('surats'));
    }

    public function create(): View
    {
        return view('surat-izin.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        unset($data['hapus_ttd']);

        $data += $this->identitas($request);
        $data['user_id'] = $request->user()->id;
        $data['tanggal_surat'] = now()->toDateString();
        $data['status'] = 'pending';

        $surat = DB::transaction(function () use ($data) {
            return SuratIzin::create($data);
        });

        $this->logService->catat(
            'create_surat_izin',
            $surat,
            null,
            ['info' => 'Membuat surat izin baru']
        );

        return $this->successResponse(
            'Surat izin berhasil dibuat.',
            'surat-izin.show',
            [$surat]
        );
    }

    public function show(Request $request, SuratIzin $suratIzin): View
    {
        $this->authorizeView($request, $suratIzin);

        return view('surat-izin.show', ['surat' => $suratIzin]);
    }

    public function edit(Request $request, SuratIzin $suratIzin): View
    {
        $this->authorizeEdit($request, $suratIzin);

        return view('surat-izin.edit', ['surat' => $suratIzin]);
    }

    public function update(Request $request, SuratIzin $suratIzin): RedirectResponse
    {
        $this->authorizeEdit($request, $suratIzin);

        $data = $request->validate($this->rules());
        $hapus = ! empty($data['hapus_ttd']);
        unset($data['hapus_ttd']);

        if (empty($data['tanda_tangan'])) {
            if ($hapus) {
                $data['tanda_tangan'] = null;
            } else {
                unset($data['tanda_tangan']);
            }
        }

        $data += $this->identitas($request);

        DB::transaction(function () use ($suratIzin, $data) {
            $before = $suratIzin->toArray();
            $suratIzin->update($data);

            $this->logService->catat(
                'update_surat_izin',
                $suratIzin,
                $before,
                $suratIzin->fresh()->toArray()
            );
        });

        return $this->successResponse(
            'Surat izin berhasil diperbarui.',
            'surat-izin.show',
            [$suratIzin]
        );
    }

    public function pdf(Request $request, SuratIzin $suratIzin): Response
    {
        $this->authorizeView($request, $suratIzin);

        $pdf = Pdf::loadView('surat-izin.pdf', ['surat' => $suratIzin])
            ->setPaper('a4', 'portrait');

        $file = 'surat-izin-'.$suratIzin->id.'.pdf';

        return $request->has('download') ? $pdf->download($file) : $pdf->stream($file);
    }

    private function authorizeView(Request $request, SuratIzin $surat): void
    {
        abort_unless(
            Peran::isAdmin() || $surat->user_id === $request->user()->id,
            403
        );
    }

    private function authorizeEdit(Request $request, SuratIzin $surat): void
    {
        abort_unless($surat->user_id === $request->user()->id, 403);
        abort_unless($surat->status === 'pending', 403, 'Surat yang sudah diproses tidak bisa diedit');
    }

    private function rules(): array
    {
        return [
            'tanggal_izin' => ['required', 'date'],
            'alasan' => ['required', 'string', 'max:500'],
            'tanda_tangan' => ['nullable', 'string', 'max:500000', 'regex:/^data:image\/png;base64,[A-Za-z0-9+\/]+=*$/'],
            'hapus_ttd' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array{nama: string, jabatan: string}
     */
    private function identitas(Request $request): array
    {
        return [
            'nama' => $request->user()->name,
            'jabatan' => Peran::data()['label'],
        ];
    }
}
