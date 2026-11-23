<?php

use App\Http\Middleware\PastikanPelangganLogin;
use App\Http\Middleware\PastikanPeran;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'peran' => PastikanPeran::class,
            'pelanggan.auth' => PastikanPelangganLogin::class,
        ]);

        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->is('order/*') || $request->routeIs('pelanggan.*')) {
                return route('pelanggan.katalog');
            }

            return $request->user()?->peran === 'admin' ? '/panel/admin' : '/panel';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
