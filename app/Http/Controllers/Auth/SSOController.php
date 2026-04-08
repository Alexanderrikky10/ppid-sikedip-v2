<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SSOController extends Controller
{
    /**
     * Arahkan pengguna ke halaman login Keycloak.
     */
    public function redirect()
    {
        return Socialite::driver('keycloak')->redirect();
    }

    /**
     * Tangani callback dari Keycloak setelah autentikasi berhasil.
     */
    public function callback()
    {
        try {
            $keycloakUser = Socialite::driver('keycloak')->user();
        } catch (\Exception $e) {
            Log::error('SSO_CALLBACK_ERROR: ' . $e->getMessage());
            return redirect('/')->with('error', 'Gagal melakukan autentikasi dengan SSO.');
        }

        // Tentukan role pengguna berdasarkan data dari Keycloak
        $keycloakRoles = $keycloakUser->user['roles'] ?? [];
        $userRole = 'staff'; // Role default
        if (in_array('admin', $keycloakRoles)) {
            $userRole = 'admin';
        }

        // Buat atau perbarui data pengguna di database lokal
        $localUser = User::updateOrCreate(
            [
                'keycloak_id' => $keycloakUser->getId(),
            ],
            [
                'name' => $keycloakUser->getName(),
                'email' => $keycloakUser->getEmail(),
                'nip' => $keycloakUser->user['nip'] ?? null,
                'role' => $userRole,
            ]
        );

        // Login-kan pengguna ke dalam aplikasi
        Auth::login($localUser, true);

        // Arahkan pengguna berdasarkan role mereka
        if ($localUser->role === 'admin') {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/staff');
    }

    /**
     * Tangani proses logout dari aplikasi dan Keycloak.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Bangun URL logout Keycloak
        $logoutUrl = rtrim(config('services.keycloak.base_url'), '/home_page')
            . '/realms/' . config('services.keycloak.realm')
            . '/protocol/openid-connect/logout'
            . '?client_id=' . config('services.keycloak.client_id')
            . '&post_logout_redirect_uri=' . urlencode(url('/'));

        return redirect($logoutUrl);
    }
}