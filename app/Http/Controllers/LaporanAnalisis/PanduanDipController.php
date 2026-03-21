<?php

namespace App\Http\Controllers\LaporanAnalisis;

use App\Http\Controllers\Controller;
use App\Models\PanduanPenyusunanDip; // Pastikan model sudah dibuat
use Illuminate\Support\Facades\Storage;

class PanduanDipController extends Controller
{
    public function panduanDip()
    {
        // Ambil semua data panduan dari database
        $panduan = PanduanPenyusunanDip::latest()->get();

        // Ambil data pertama untuk pratinjau utama (default)
        $utama = $panduan->first();

        $fileUrl = null;
        $extension = null;

        if ($utama) {
            // Generate URL dari MinIO
            $fileUrl = Storage::disk('minio')->temporaryUrl(
                $utama->file_path,
                now()->addMinutes(60)
            );
            $extension = pathinfo($utama->file_path, PATHINFO_EXTENSION);
        }

        return view('content.laporan-analisis.panduan-penyusunan-dip', compact('panduan', 'utama', 'fileUrl', 'extension'));
    }
}