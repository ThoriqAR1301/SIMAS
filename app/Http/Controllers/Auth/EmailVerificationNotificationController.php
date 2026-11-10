<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $throttleKey = 'send-verification:'.$user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak permintaan. Silakan coba lagi dalam {$seconds} detik",
            ]);
        }

        $user->sendEmailVerificationNotification();

        RateLimiter::hit($throttleKey, 60);

        Log::info('Email verifikasi dikirim ulang', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);

        return back()->with('status', 'Tautan verifikasi baru telah dikirim ke email Anda. Silakan periksa kotak masuk atau folder spam');
    }
}
