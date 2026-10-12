<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use App\Services\ApprovalService;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct(private ApprovalService $service) {}

    public function index()
    {
        $user = $this->authenticatedUser();
        $step = $user->isAdminTu() ? 1 : 2;

        $pengajuan = PengajuanSurat::with(['user', 'blanko'])->menungguApproval($step)->latest()->paginate(15);

        return view('approval.index', compact('pengajuan', 'step'));
    }

    public function verify(Request $request, PengajuanSurat $pengajuan)
    {
        $this->authorize('verify', $pengajuan);

        $data = $request->validate([
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->service->verifikasiAdmin($pengajuan, $this->authenticatedUser(), $data['catatan'] ?? null);

        return back()->with('success', 'Pengajuan Diteruskan Ke Pimpinan');
    }

    public function approve(Request $request, PengajuanSurat $pengajuan)
    {
        $this->authorize('approve', $pengajuan);

        $data = $request->validate([
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->service->approvePimpinan($pengajuan, $this->authenticatedUser(), $data['catatan'] ?? null);

        return back()->with('success', 'Pengajuan Disetujui & Nomor Surat Telah Di-Generate');
    }

    public function reject(Request $request, PengajuanSurat $pengajuan)
    {
        $data = $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ]);

        $step = $pengajuan->current_step;
        abort_unless(in_array($step, [1, 2]), 403);

        if ($step === 1) {
            abort_unless($this->authenticatedUser()->isAdminTu(), 403);
        } else {
            abort_unless($this->authenticatedUser()->isPimpinan(), 403);
        }

        $this->service->reject($pengajuan, $this->authenticatedUser(), $data['catatan']);

        return back()->with('success', 'Pengajuan Ditolak');
    }

    public function mintaRevisi(Request $request, PengajuanSurat $pengajuan)
    {
        $data = $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ]);

        $this->service->mintaRevisi($pengajuan, $this->authenticatedUser(), $data['catatan']);

        return back()->with('success', 'Pengajuan Dikembalikan Ke Guru Untuk Direvisi');
    }
}
