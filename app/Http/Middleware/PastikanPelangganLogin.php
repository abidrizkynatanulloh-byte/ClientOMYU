<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PastikanPelangganLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('pelanggan')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Silakan login terlebih dahulu.'], 401);
            }

            return redirect()->guest(route('pelanggan.login'))
                ->with('galat', 'Silakan login dengan nomor HP untuk memesan.');
        }

        return $next($request);
    }
}
