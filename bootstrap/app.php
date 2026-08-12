<?php

use App\Exceptions\ExceptionMessages;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->renderable(function (Throwable $e, Request $request) {
            // Validation errors: keep Laravel's structured per-field errors
            // (now in Indonesian) for the API client, and let the web layer
            // redirect back with the errors by default.
            if ($e instanceof ValidationException) {
                if ($request->is('api/*') || $request->expectsJson()) {
                    return response()->json([
                        'message' => 'Terdapat kesalahan pada data yang Anda kirimkan. Silakan periksa kembali.',
                        'errors' => $e->errors(),
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return null;
            }

            $status = ExceptionMessages::status($e);
            $message = ExceptionMessages::message($e);

            // API / JSON responses: always return a friendly Indonesian message.
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => $message], $status);
            }

            // Web responses: surface the friendly message through the existing
            // error flash (SweetAlert) so non-technical users understand it.
            if ($status >= 400 && $status < 500) {
                return back()->withErrors(['_error' => $message])->withInput();
            }

            return redirect()->route('dashboard')->withErrors(['_error' => $message]);
        });
    })->create();
