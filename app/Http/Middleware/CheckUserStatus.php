<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Token tidak valid atau telah kedaluwarsa.'], 401);
        }

        if (Auth::check() && Auth::user()->status !== 'Aktif') {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Akun anda tidak aktif.
            '], 403);
        }

        return $next($request);
    }
}
