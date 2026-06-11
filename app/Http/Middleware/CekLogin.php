<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekLogin
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        // Cek apakah user sudah login
        if (!session()->has('user_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek role jika ada parameter role
        if ($role && session('user_role') !== $role) {
            return redirect('/login')->with('error', 'Akses ditolak. Anda tidak memiliki izin.');
        }

        return $next($request);
    }
}