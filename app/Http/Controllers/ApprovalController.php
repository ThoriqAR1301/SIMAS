<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Services\ApprovalService;
use App\Services\LogAktivitasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function __construct(
        private readonly ApprovalService $service,
        private readonly LogAktivitasService $logService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        $step = $user->isAdminTu() ? 1 : 2;

        $pengajuan = PengajuanSurat::with(['user', 'blanko'])
            ->menungguApproval($step)
            ->latest()
            ->paginate(15);

        return view('approval.index', compact('pengajuan', 'step'));
    }

    public function verify(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        $this->authorize('verify', $pengajuan);

        $data = $this->validasiCatatan($request);

        $this->service->verifikasiAdmin($pengajuan, $request->user(), $data['catatan'] ?? null);

        $this->logService->catat(
            'verify_pengajuan',
            $pengajuan,
            null,
            ['info' => 'Admin TU memverifikasi pengajuan']
        );

        return back()->with('success', 'Pengajuan berhasil diverifikasi dan diteruskan ke Pimpinan');
    }

    public function approve(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        $this->authorize('approve', $pengajuan);

        $data = $this->validasiCatatan($request);

        $this->service->approvePimpinan($pengajuan, $request->user(), $data['catatan'] ?? null);

        $this->logService->catat(
            'approve_pengajuan',
            $pengajuan,
            null,
            ['info' => 'Pimpinan menyetujui pengajuan secara final']
        );

        return back()->with('success', 'Pengajuan disetujui & Nomor Surat telah di-generate secara otomatis');
    }

    public function reject(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        $data = $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        $step = $pengajuan->current_step;

        abort_unless(in_array($step, [1, 2], true), 403);

        if ($step === 1) {
            abort_unless($user->isAdminTu(), 403);
        } else {
            abort_unless($user->isPimpinan(), 403);
        }

        $this->service->reject($pengajuan, $user, $data['catatan']);

        $this->logService->catat(
            'reject_pengajuan',
            $pengajuan,
            null,
            ['info' => 'Pengajuan ditolak pada step '.$step.'. Alasan : '.$data['catatan']]
        );

        return back()->with('success', 'Pengajuan telah ditolak');
    }

    public function mintaRevisi(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        $data = $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ]);

        $this->service->mintaRevisi($pengajuan, $request->user(), $data['catatan']);

        $this->logService->catat(
            'revisi_pengajuan',
            $pengajuan,
            null,
            ['info' => 'Pengajuan dikembalikan ke Guru untuk direvisi. Catatan : '.$data['catatan']]
        );

        return back()->with('success', 'Pengajuan dikembalikan ke Guru untuk direvisi');
    }

    private function validasiCatatan(Request $request): array
    {
        return $request->validate([
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
