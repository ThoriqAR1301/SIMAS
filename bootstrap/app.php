<?php

declare(strict_types=1);

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (Throwable $e, Request $request) {

            if ($e instanceof AuthorizationException) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Anda tidak memiliki hak akses.'], 403);
                }
            }

            if ($e instanceof AuthenticationException) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Sesi Anda telah berakhir. Silakan login kembali.'], 401);
                }
            }

            if ($e instanceof NotFoundHttpException) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Halaman atau data tidak ditemukan.'], 404);
                }
            }
        });
    })->create();
