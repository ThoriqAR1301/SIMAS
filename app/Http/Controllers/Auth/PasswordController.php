<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LogAktivitasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function __construct(
        private readonly LogAktivitasService $logService
    ) {}

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->logService->catat(
            'update_password',
            $user,
            null,
            ['info' => 'Pengguna memperbarui kata sandi akun']
        );

        $request->session()->regenerate();

        return back()->with('success', 'Kata sandi Anda berhasil diperbarui. Gunakan kata sandi baru pada sesi login berikutnya');
    }
}
