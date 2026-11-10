<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Mendapatkan user yang sedang terautentikasi.
     *
     * @throws HttpException
     */
    protected function authenticatedUser(): User
    {
        $user = Auth::user();

        abort_unless($user instanceof User, 401);

        return $user;
    }

    protected function successResponse(string $message, ?string $route = null, array $parameters = []): RedirectResponse
    {
        $redirect = $route ? redirect()->route($route, $parameters) : back();

        return $redirect->with('success', $message);
    }

    protected function errorResponse(string $message, ?string $route = null, array $parameters = []): RedirectResponse
    {
        $redirect = $route ? redirect()->route($route, $parameters) : back();

        return $redirect->with('error', $message);
    }
}
