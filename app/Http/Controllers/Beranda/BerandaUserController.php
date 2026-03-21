<?php

namespace App\Http\Controllers\Beranda;

use App\Http\Controllers\Controller;
use App\Models\BerandaContent;

class BerandaUserController extends Controller
{
    public function index()
    {
        // Ambil satu konten yang aktif
        $BerandaContent = BerandaContent::where('is_active', true)->first();

        // Jika tidak ada yang aktif, ambil yang paling baru
        if (!$BerandaContent) {
            $BerandaContent = BerandaContent::latest()->first();
        }

        return view('content.beranda-user.beranda', [
            'BerandaContent' => $BerandaContent
        ]);
    }
}