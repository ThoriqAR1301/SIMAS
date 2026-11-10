<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PengajuanSurat\StorePengajuanRequest;
use App\Http\Requests\PengajuanSurat\UpdatePengajuanRequest;
use App\Models\ApprovalLog;
use App\Models\BlankoSurat;
use App\Models\PengajuanSurat;
use App\Services\LogAktivitasService;
use App\Services\PengajuanSuratService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PengajuanSuratController extends Controller
{
    public function __construct(
        private readonly PengajuanSuratService $service,
        private readonly LogAktivitasService $logService,
    ) {}

    public function index(Request $request): View
    {
        $pengajuan = PengajuanSurat::filterIndex(
            $request->only(['status', 'q', 'dari', 'sampai']),
            $request->user(),
        )->paginate(15)->withQueryString();

        return view('pengajuan.index', [
            'pengajuan' => $pengajuan,
            'filters' => $request->only(['status', 'q', 'dari', 'sampai']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', PengajuanSurat::class);

        return view('pengajuan.create', [
            'blanko' => BlankoSurat::aktif()->orderBy('nama')->get(),
        ]);
    }

    public function store(StorePengajuanRequest $request): RedirectResponse
    {
        $user = $request->user();
        $pengajuan = $this->service->buat($user, $request->validated());

        $this->logService->catat('create_pengajuan', $pengajuan, null, $pengajuan->toArray());

        if ($request->input('aksi') === 'submit') {
            $this->service->submit($pengajuan);

            return $this->successResponse('Pengajuan berhasil diajukan & menunggu verifikasi', 'pengajuan.show', [$pengajuan]);
        }

        return $this->successResponse('Pengajuan berhasil disimpan sebagai draft', 'pengajuan.show', [$pengajuan]);
    }

    public function show(PengajuanSurat $pengajuan): View
    {
        $this->authorize('view', $pengajuan);

        $pengajuan->load([
            'blanko', 'user', 'reviewer',
            'approvalLogs.approver',
            'lampiran',
        ]);

        return view('pengajuan.show', compact('pengajuan'));
    }

    public function edit(PengajuanSurat $pengajuan): View
    {
        $this->authorize('update', $pengajuan);

        return view('pengajuan.edit', [
            'pengajuan' => $pengajuan,
            'blanko' => BlankoSurat::aktif()->orderBy('nama')->get(),
        ]);
    }

    public function update(UpdatePengajuanRequest $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        $this->authorize('update', $pengajuan);

        $this->service->update($pengajuan, $request->validated());

        if ($request->input('aksi') === 'submit') {
            $this->service->submit($pengajuan);

            return $this->successResponse('Pengajuan berhasil diperbarui & diajukan', 'pengajuan.show', [$pengajuan]);
        }

        return $this->successResponse('Pengajuan berhasil diperbarui', 'pengajuan.show', [$pengajuan]);
    }

    public function destroy(PengajuanSurat $pengajuan): RedirectResponse
    {
        $this->authorize('delete', $pengajuan);

        $this->service->hapus($pengajuan);

        return $this->successResponse('Pengajuan berhasil dihapus', 'pengajuan.index');
    }

    public function submit(PengajuanSurat $pengajuan): RedirectResponse
    {
        $this->authorize('submit', $pengajuan);

        $this->service->submit($pengajuan);

        return back()->with('success', 'Pengajuan berhasil diajukan');
    }

    public function timeline(PengajuanSurat $pengajuan): JsonResponse
    {
        $this->authorize('view', $pengajuan);

        return response()->json([
            'status' => $pengajuan->status->value,
            'label' => $pengajuan->status->label(),
            'logs' => $pengajuan->approvalLogs()
                ->with('approver')
                ->get()
                ->map(fn ($log) => $this->formatTimelineLog($log)),
        ]);
    }

    public function pdf(PengajuanSurat $pengajuan): Response
    {
        $this->authorize('view', $pengajuan);

        abort_unless($pengajuan->sudahDisetujui(), 403, 'Surat belum disetujui.');

        $pengajuan->load(['user', 'blanko', 'reviewer']);

        $pdf = Pdf::loadView('pengajuan.pdf', [
            'pengajuan' => $pengajuan,

        ])->setPaper('a4', 'portrait');

        $safeNomor = str_replace(['/', '\\'], '-', (string) $pengajuan->nomor_surat);
        $filename = 'Surat-'.$safeNomor.'.pdf';

        return $pdf->download($filename);
    }

    private function formatTimelineLog(ApprovalLog $log): array
    {
        return [
            'step' => $log->step,
            'label_step' => $log->label_step,
            'aksi' => $log->aksi,
            'label_aksi' => $log->label_aksi,
            'catatan' => $log->catatan,
            'approver' => $log->approver->name ?? 'Sistem',
            'acted_at' => $log->acted_at->format('d M Y H:i'),
        ];
    }
}
