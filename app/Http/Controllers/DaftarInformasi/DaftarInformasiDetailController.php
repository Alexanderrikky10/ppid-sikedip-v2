<?php

namespace App\Http\Controllers\DaftarInformasi;

use App\Models\Informasi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class DaftarInformasiDetailController extends Controller
{
    // 1. METHOD AJAX POPUP
    public function show($id)
    {
        // Eager load detail relations
        $info = Informasi::with(['kategoriJenisInformasi', 'klasifikasiInformasi', 'perangkatDaerah'])->findOrFail($id);

        // --- PERBAIKAN: Hitung Ukuran File via MinIO ---
        $fileSize = '0 KB';
        $path = $info->media;

        // Cek file di MinIO, bukan di folder public local
        if (Storage::disk('minio')->exists($path)) {
            try {
                $bytes = Storage::disk('minio')->size($path); // Ambil ukuran dari MinIO
                $fileSize = $bytes >= 1048576
                    ? number_format($bytes / 1048576, 2) . ' MB'
                    : number_format($bytes / 1024, 2) . ' KB';
            } catch (\Exception $e) {
                $fileSize = 'Unknown'; // Fallback jika gagal baca metadata
            }
        }

        $extension = pathinfo($info->media, PATHINFO_EXTENSION);

        return response()->json([
            'id' => $info->id,
            'slug' => $info->slug,
            'judul_informasi' => $info->judul_informasi,
            'nomor_dokumen' => str_pad($info->id, 8, '0', STR_PAD_LEFT),
            'tanggal_publikasi' => \Carbon\Carbon::parse($info->created_at)->translatedFormat('l, d F Y H:i'),
            'jenis_informasi' => $info->kategoriJenisInformasi->nama_kategori ?? '-',
            'klasifikasi_informasi' => $info->klasifikasiInformasi->nama_klasifikasi ?? '-', // Pastikan nama relasi benar
            'tipe_dokumen' => strtoupper($extension),
            'ukuran_berkas' => $fileSize,
            'penerbit' => $info->pj_penerbit_informasi,
            'file_url' => route('informasi.download', $info->slug)
        ]);
    }

    // 2. METHOD HALAMAN DETAIL
    public function detailPage($slug)
    {
        $info = Informasi::where('slug', $slug)
            ->with([
                'kategoriJenisInformasi',
                'klasifikasiInformasi',
                'perangkatDaerah',
                'kategoriInformasi'
            ])->firstOrFail();

        // Increment Views
        $info->increment('views_count');

        // Sidebar
        $relatedInfos = Informasi::where('id', '!=', $info->id)
            ->where('perangkat_daerah_id', $info->perangkat_daerah_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // --- PERBAIKAN: Hitung Ukuran File via MinIO ---
        $fileSize = '0 KB';
        $path = $info->media;

        if (Storage::disk('minio')->exists($path)) {
            try {
                $bytes = Storage::disk('minio')->size($path);
                $fileSize = $bytes >= 1048576
                    ? number_format($bytes / 1048576, 2) . ' MB'
                    : number_format($bytes / 1024, 2) . ' KB';
            } catch (\Exception $e) {
                $fileSize = 'Unknown';
            }
        }
        $extension = pathinfo($info->media, PATHINFO_EXTENSION);

        return view('content.daftar-informasi.detail-informasi', compact('info', 'relatedInfos', 'fileSize', 'extension'));
    }

    // 3. METHOD DOWNLOAD FILE
    public function downloadFile($slug)
    {
        $informasi = Informasi::where('slug', $slug)->firstOrFail();

        // PERHATIKAN: Pastikan nama kolom di database Anda 'downloads_count' atau 'download_count'
        // Jika error "column not found", sesuaikan nama ini.
        $informasi->increment('downloads_count');

        $path = $informasi->media;

        // Cek file di MinIO
        if (Storage::disk('minio')->exists($path)) {

            // Bersihkan nama file agar aman dan bagus saat didownload user
            $filename = \Str::slug($informasi->judul_informasi) . '.' . pathinfo($path, PATHINFO_EXTENSION);

            // Return stream download dari MinIO
            return Storage::disk('minio')->download($path, $filename);
        }

        return abort(404, 'File dokumen tidak ditemukan di server.');
    }
}