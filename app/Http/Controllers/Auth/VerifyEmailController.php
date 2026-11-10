<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LogAktivitasService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __construct(
        private readonly LogAktivitasService $logService
    ) {}

    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()
                ->intended(route('dashboard', absolute: false))
                ->with('info', 'Email Anda sudah diverifikasi sebelumnya');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            $this->logService->catat(
                'verify_email',
                $user,
                null,
                ['info' => 'Pengguna berhasil memverifikasi alamat email']
            );
        }

        return redirect()
            ->intended(route('dashboard', absolute: false))
            ->with('success', 'Selamat! Email Anda telah berhasil diverifikasi');
    }
}
