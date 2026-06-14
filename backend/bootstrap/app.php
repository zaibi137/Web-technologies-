<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ── Register the 'admin' alias so Route::middleware('admin') works ──
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);

        // ── CORS: allow the Live Server origin (127.0.0.1:5500) ──
        $middleware->validateCsrfTokens(except: ['api/*']);

        // Tell Sanctum which domains get stateful cookies (not needed for
        // token-based auth, but keeps things consistent).
        $middleware->trustHosts(at: ['localhost', '127.0.0.1']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Return JSON 401 instead of a login redirect for API routes
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
        });
    })->create();