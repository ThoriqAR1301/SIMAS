<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role?->name, $roles)) {
            abort(403, 'Anda Tidak Memiliki Hak Akses');
        }

        return $next($request);
    }
}