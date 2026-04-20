<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\KategoriInformasi;
use App\Models\PerangkatDaerah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PermohonanUser extends Controller
{
    public function create()
    {
        $kategoriList = KategoriInformasi::all();
        $perangkatDaerahList = PerangkatDaerah::with('kategoriInformasi')->get();

        return view('auth.content-permohonan', compact('kategoriList', 'perangkatDaerahList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:users,nip',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'hak_akses' => 'required|exists:kategori_informasis,id',
            'perangkat_daerah_id' => 'required|exists:perangkat_daerahs,id',
            'daerah' => 'required|string|max:100',
            'biro' => 'required|string|max:100',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'hak_akses.required' => 'Kategori hak akses wajib dipilih.',
            'perangkat_daerah_id.required' => 'Perangkat daerah wajib dipilih.',
            'daerah.required' => 'Daerah wajib diisi.',
            'biro.required' => 'Biro wajib diisi.',
        ]);

        User::create([
            'name' => $validated['name'],
            'nip' => $validated['nip'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'staff', // otomatis staff
            'hak_akses' => $validated['hak_akses'],
            'perangkat_daerah_id' => $validated['perangkat_daerah_id'],
            'daerah' => $validated['daerah'],
            'biro' => $validated['biro'],
            'is_active' => false, //set default false untuk user yang login lewat ini 
        ]);

        return redirect()->route('permohonan.create')
            ->with('success', 'Permohonan akun berhasil dikirim! Silakan tunggu konfirmasi dari administrator.');
    }
}