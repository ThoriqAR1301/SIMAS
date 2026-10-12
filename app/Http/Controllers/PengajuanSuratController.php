<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengajuanSurat\StorePengajuanRequest;
use App\Http\Requests\PengajuanSurat\UpdatePengajuanRequest;
use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Services\LogAktivitasService;
use App\Services\PengajuanSuratService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PengajuanSuratController extends Controller
{
    public function __construct(
        private PengajuanSuratService $service,
        private LogAktivitasService $logService,
    ) {}

    public function index(Request $request)
    {
        $user = $this->authenticatedUser();
        $query = PengajuanSurat::with(['blanko', 'user'])
            ->when($user->isGuru(), fn ($query) => $query->milik((int) $user->getKey()))
            ->when($request->status, fn ($query, $status) => $query->status($status))
            ->when($request->q, fn ($query, $keyword) => $query->cari($keyword))
            ->when(
                $request->dari || $request->sampai,
                fn ($query) => $query->rentangTanggal($request->dari, $request->sampai),
            )
            ->latest();

        return view('pengajuan.index', [
            'pengajuan' => $query->paginate(15)->withQueryString(),
            'filters' => $request->only(['status', 'q', 'dari', 'sampai']),
        ]);
    }

    public function create()
    {
        $this->authorize('create', PengajuanSurat::class);

        return view('pengajuan.create', [
            'blanko' => BlankoSurat::aktif()->orderBy('nama')->get(),
        ]);
    }

    public function store(StorePengajuanRequest $request)
    {
        $pengajuan = $this->service->buat($this->authenticatedUser(), $request->validated());

        $this->logService->catat('create', $pengajuan, null, $pengajuan->toArray());

        if ($request->input('aksi') === 'submit') {
            $this->service->submit($pengajuan);

            return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Pengajuan Berhasil Diajukan & Menunggu Verifikasi');
        }

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Pengajuan Disimpan Sebagai Draft');
    }

    public function show(PengajuanSurat $pengajuan)
    {
        $this->authorize('view', $pengajuan);

        $pengajuan->load([
            'blanko', 'user', 'reviewer',
            'approvalLogs.approver',
            'lampiran',
        ]);

        return view('pengajuan.show', compact('pengajuan'));
    }

    public function edit(PengajuanSurat $pengajuan)
    {
        $this->authorize('update', $pengajuan);

        return view('pengajuan.edit', [
            'pengajuan' => $pengajuan,
            'blanko' => BlankoSurat::aktif()->orderBy('nama')->get(),
        ]);
    }

    public function update(UpdatePengajuanRequest $request, PengajuanSurat $pengajuan)
    {
        $this->authorize('update', $pengajuan);

        $before = $pengajuan->toArray();
        $this->service->update($pengajuan, $request->validated());
        $this->logService->catat('update', $pengajuan, $before, $pengajuan->fresh()->toArray());

        if ($request->input('aksi') === 'submit') {
            $this->service->submit($pengajuan);

            return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Pengajuan Berhasil Diperbarui & Diajukan');
        }

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Pengajuan Berhasil Diperbarui');
    }

    public function destroy(PengajuanSurat $pengajuan)
    {
        $this->authorize('delete', $pengajuan);

        $this->logService->catat('delete', $pengajuan, $pengajuan->toArray(), null);
        $this->service->hapus($pengajuan);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan Berhasil Dihapus');
    }

    public function submit(PengajuanSurat $pengajuan)
    {
        $this->authorize('submit', $pengajuan);

        $this->service->submit($pengajuan);

        return back()->with('success', 'Pengajuan Berhasil Diajukan');
    }

    public function timeline(PengajuanSurat $pengajuan)
    {
        $this->authorize('view', $pengajuan);

        return response()->json([
            'status' => $pengajuan->status->value,
            'label' => $pengajuan->status->label(),
            'logs' => $pengajuan->approvalLogs()->with('approver')->get()->map(fn ($log) => [
                'step' => $log->step,
                'label_step' => $log->labelStep(),
                'aksi' => $log->aksi,
                'label_aksi' => $log->labelAksi(),
                'catatan' => $log->catatan,
                'approver' => $log->approver->name,
                'acted_at' => $log->acted_at->format('d M Y H:i'),
            ]),
        ]);
    }

    public function pdf(PengajuanSurat $pengajuan)
    {
        $this->authorize('view', $pengajuan);

        abort_unless($pengajuan->sudahDisetujui(), 403, 'Surat Belum Disetujui');

        $pengajuan->load(['user', 'blanko', 'reviewer']);

        $qrPath = storage_path('app/public/qr/'.$pengajuan->qr_token.'.png');

        $pdf = Pdf::loadView('pengajuan.pdf', [
            'pengajuan' => $pengajuan,
            'qrPath' => file_exists($qrPath) ? $qrPath : null,
        ])->setPaper('a4', 'portrait');

        $filename = 'Surat-'.str_replace('/', '-', $pengajuan->nomor_surat).'.pdf';

        return $pdf->download($filename);
    }
}
