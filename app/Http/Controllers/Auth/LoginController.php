<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login dengan kredensial yang diberikan
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if (auth()->user()->role === 'admin') {
                return redirect()->intended('/admin');
            } elseif (auth()->user()->role === 'staff') {
                return redirect()->intended('/staff');
            } else {
                auth()->logout();
                return back()->withErrors([
                    'email' => 'You are not authorized to access this area.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}