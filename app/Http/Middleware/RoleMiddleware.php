<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user instanceof User) {
            return redirect()->guest(route('login'));
        }

        $user->loadMissing('role');

        if (! in_array($user->role?->name, $roles, true)) {

            Log::warning('Percobaan akses tidak sah (Unauthorized Access).', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role?->name ?? 'Tidak Ada Role',
                'required_roles' => $roles,
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses Ditolak. Anda tidak memiliki hak akses untuk tindakan ini.',
                ], Response::HTTP_FORBIDDEN);
            }

            abort(Response::HTTP_FORBIDDEN, 'Anda Tidak Memiliki Hak Akses');
        }

        return $next($request);
    }
}
