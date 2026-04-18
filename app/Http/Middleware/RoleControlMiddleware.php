<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleControlMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        // 1. PROTEKSI GUEST (BELUM LOGIN)
        if (!Auth::check()) {
            // Jika mencoba akses /admin tanpa login, lempar ke login admin
            if (str_starts_with($path, 'admin')) {
                return redirect()->route('login');
            }

            // Jika mencoba akses /staff tanpa login, lempar ke login staff
            if (str_starts_with($path, 'staff')) {
                return redirect()->route('login');
            }

            // Jika akses rute lain yang terpasang middleware ini, lempar ke login umum
            return redirect('/login');
        }

        $user = Auth::user();

        // 2. LOGIKA REDIRECT BERDASARKAN ROLE (SUDAH LOGIN)

        // Case: Admin mencoba masuk ke area Staff -> Kembalikan ke Admin
        if (str_starts_with($path, 'staff') && $user->role === 'admin') {
            return redirect('/admin')->with('warning', 'Anda dialihkan kembali ke Dashboard Admin.');
        }

        // Case: Staff mencoba masuk ke area Admin -> Kembalikan ke Staff
        if (str_starts_with($path, 'admin') && $user->role === 'staff') {
            return redirect('/staff')->with('warning', 'Anda tidak memiliki izin akses Admin.');
        }

        // Case: User memiliki role lain (misal: 'user' biasa) tapi mencoba akses /admin atau /staff
        if (
            (str_starts_with($path, 'admin') || str_starts_with($path, 'staff')) &&
            !in_array($user->role, ['admin', 'staff'])
        ) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}