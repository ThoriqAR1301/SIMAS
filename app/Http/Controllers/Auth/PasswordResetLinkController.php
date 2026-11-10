<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Memproses permintaan tautan reset password.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $throttleKey = 'forgot-password:'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak permintaan. Silakan coba lagi dalam {$seconds} detik",
            ]);
        }

        RateLimiter::hit($throttleKey, 60);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            Log::info('Permintaan tautan reset password berhasil.', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            return back()->with('success', 'Tautan reset kata sandi telah dikirim ke email Anda. Silakan periksa kotak masuk atau folder spam');
        }

        Log::warning('Permintaan tautan reset password gagal', [
            'email' => $request->email,
            'status' => $status,
            'ip' => $request->ip(),
        ]);

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
