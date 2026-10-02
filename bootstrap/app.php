<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\ForcePasswordChange;
use App\Http\Middleware\ManageActiveUp3;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        // Middleware ini jalan otomatis untuk SEMUA route yang butuh login (append ke grup 'web')
        $middleware->appendToGroup('web', [
            ForcePasswordChange::class,
            ManageActiveUp3::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $pesan = 'Ukuran file yang diupload melebihi batas maksimal server (50 MB).';
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $pesan,
                    'errors' => ['file' => [$pesan]],
                ], 413);
            }

            return back()->with('error', $pesan);
        });
    })->create();
