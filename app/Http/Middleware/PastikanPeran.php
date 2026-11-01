<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PastikanPeran
{
    public function handle(Request $request, Closure $next, string ...$peran): Response
    {
        $pengguna = $request->user();

        if (! $pengguna || ! $pengguna->aktif || ! in_array($pengguna->peran, $peran, true)) {
            abort(403, 'Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}