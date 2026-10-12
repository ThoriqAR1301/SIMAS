<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return redirect()->route('login');
        }

        if (! in_array($user->role?->name, $roles, true)) {
            abort(403, 'Anda Tidak Memiliki Hak Akses');
        }

        return $next($request);
    }
}
