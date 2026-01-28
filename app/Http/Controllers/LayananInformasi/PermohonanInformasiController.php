<?php

namespace App\Http\Controllers\LayananInformasi;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PerangkatDaerah;
use App\Http\Controllers\Controller;
use App\Models\PermohonanInformasi; // Pastikan Model ini ada
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk upload file

class PermohonanInformasiController extends Controller
{
    public function index()
    {
        // 1. Ambil Induk (Parent) dan Eager Load Children-nya
        $parents = PerangkatDaerah::whereNull('parent_id')
            ->with([
                'children' => function ($query) {
                    $query->orderBy('nama_perangkat_daerah', 'asc');
                }
            ])
            ->orderBy('nama_perangkat_daerah', 'asc')
            ->get();

        // 2. Collection baru untuk menampung hasil urutan dropdown
        $sortedPerangkatDaerah = collect();

        foreach ($parents as $parent) {
            $parent->setAttribute('is_header', true);
            $sortedPerangkatDaerah->push($parent);

            if ($parent->kategori_informasi_id == 2) {
                continue;
            }

            // C. Masukkan ANAK-ANAKNYA (Untuk Pemprov, BUMD, dll)
            foreach ($parent->children as $child) {
                $child->setAttribute('is_header', false);
                $sortedPerangkatDaerah->push($child);
            }
        }

        return view('content.layanan-informasi.permohonan-informasi', [
            'perangkatDaerahs' => $sortedPerangkatDaerah
        ]);
    }
    public function store(Request $request)
    {
        // 1. Validasi Data (SEMUA WAJIB KECUALI FAX)
        $validatedData = $request->validate([
            'perangkat_daerah_id' => 'required|exists:perangkat_daerahs,id',
            'nama_pemohon' => 'required|string|max:255',
            'jenis_permohonan' => 'required|in:perorangan,badan_hukum,kelompok',
            'tanggal_lahir' => 'required|date', 
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan', 
            'no_identitas' => 'required|string|max:50', 
            'scan_identitas' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',  
            'alamat_lengkap' => 'required|string', 
            'nomor_fax' => 'nullable|string|max:50', // BOLEH KOSONG (Satu-satunya)
            'nomor_whatsapp' => 'required|string|max:20', 
            'alamat_email' => 'required|email|max:150', 
            'informasi_diminta' => 'required|string',
            'alasan_permintaan' => 'required|string',
            'cara_penyampaian_informasi' => 'required|string|max:100', 
            'tindak_lanjut' => 'required|string',

            // Logika: Dokumen tambahan WAJIB jika jenis permohonan BUKAN perorangan
            'dokumen_tambahan_path' => 'required_unless:jenis_permohonan,perorangan|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            // Custom Error Messages (Opsional, agar pesan lebih jelas)
            'required' => ':attribute wajib diisi.',
            'required_unless' => ':attribute wajib diunggah untuk Badan Hukum/Kelompok.',
            'mimes' => 'Format file :attribute harus :values.',
            'max' => 'Ukuran file :attribute maksimal :max kilobyte.',
        ]);

        // 2. Handle Upload File (Scan Identitas)
        if ($request->hasFile('scan_identitas')) {
            $pathScan = $request->file('scan_identitas')->store('uploads/identitas', 'public');
            $validatedData['scan_identitas'] = $pathScan;
        }

        // 3. Handle Upload File (Dokumen Tambahan)
        if ($request->hasFile('dokumen_tambahan_path')) {
            $pathDokumen = $request->file('dokumen_tambahan_path')->store('uploads/dokumen_tambahan', 'public');
            $validatedData['dokumen_tambahan_path'] = $pathDokumen;
        }

        // Generate No Registrasi
        $validatedData['no_registrasi'] = 'REG-' . date('Ymd') . '-' . rand(1000, 9999);

        // 4. Simpan ke Database
        PermohonanInformasi::create($validatedData);

        // 5. Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Permohonan informasi berhasil diajukan! Nomor Registrasi Anda: ' . $validatedData['no_registrasi']);
    }

}