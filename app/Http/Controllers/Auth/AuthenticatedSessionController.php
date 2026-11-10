<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\LogAktivitasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly LogAktivitasService $logService
    ) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $this->logService->catat(
            'login',
            $request->user(),
            null,
            ['info' => 'User berhasil masuk ke sistem']
        );

        session()->flash('success', 'Selamat datang kembali, '.$request->user()->name.'!');

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()) {
            $this->logService->catat(
                'logout',
                $request->user(),
                null,
                ['info' => 'User keluar dari sistem']
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->flash('success', 'Anda telah berhasil keluar dari sistem. Sampai jumpa!');

        return redirect('/');
    }
}
