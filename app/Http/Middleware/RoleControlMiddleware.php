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
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $path = $request->path();

        // Jika user mencoba masuk ke area admin tapi bukan admin
        if (str_starts_with($path, 'admin') && $user->role !== 'admin') {
            return redirect('/staff')->with('error', 'Anda tidak memiliki akses ke halaman Admin.');
        }

        if (str_starts_with($path, 'staff') && $user->role !== 'admin' && $user->role !== 'staff') {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}