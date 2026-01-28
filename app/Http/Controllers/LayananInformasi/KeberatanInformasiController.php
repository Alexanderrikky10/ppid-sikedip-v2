<?php

namespace App\Http\Controllers\LayananInformasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KeberatanInformasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KeberatanInformasiController extends Controller
{
    // ... Method getPermohonanByNik dan index TETAP SAMA seperti sebelumnya ...
    public function getPermohonanByNik(Request $request)
    {
        $nik = $request->query('nik');

        if (!$nik) {
            return response()->json(['error' => 'NIK wajib diisi'], 400);
        }

        // Mengambil data permohonan berdasarkan NIK
        // Kita select 'id' dan 'no_registrasi' agar di view bisa: value="id" text="no_registrasi"
        $permohonan = \App\Models\PermohonanInformasi::where('no_identitas', $nik)
            ->select('id', 'no_registrasi', 'informasi_diminta', 'nama_pemohon', 'no_identitas', 'nomor_whatsapp', 'alamat_lengkap')
            ->latest()
            ->get();

        return response()->json($permohonan);
    }

    public function index()
    {
        return view('content.layanan-informasi.keberatan-informasi');
    }

    public function store(Request $request)
    {
        // 1. Validasi Data
        $rules = [
            'permohonan_informasi_id' => 'required|exists:permohonan_informasis,id', // Validasi ID harus ada di tabel master
            'nik_pemohon' => 'required|numeric',
            'nama_pemohon' => 'required|string',
            'telepon_pemohon' => 'required|string',
            'pekerjaan' => 'required|string',
            'alamat_pemohon' => 'required|string',
            'alasan_keberatan' => 'required|array|min:1',
            'tujuan_penggunaan_informasi' => 'required|string', // Sesuai nama kolom di migration
        ];

        // Validasi Kondisional Kuasa
        if ($request->has('is_dikuasakan')) {
            $rules['nama_kuasa'] = 'required|string|max:255';
            $rules['telepon_kuasa'] = 'required|string|max:20';
            $rules['alamat_kuasa'] = 'required|string';
            $rules['surat_kuasa'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        } else {
            $rules['nama_kuasa'] = 'nullable';
            $rules['telepon_kuasa'] = 'nullable';
            $rules['alamat_kuasa'] = 'nullable';
            $rules['surat_kuasa'] = 'nullable';
        }

        $request->validate($rules, [
            'permohonan_informasi_id.required' => 'Mohon cek NIK dan pilih nomor registrasi permohonan terlebih dahulu.',
            'alasan_keberatan.required' => 'Pilih minimal satu alasan keberatan.',
            'surat_kuasa.required' => 'Surat kuasa wajib diupload jika dikuasakan.',
        ]);

        try {
            DB::beginTransaction();

            // 2. Upload File Surat Kuasa (Jika ada)
            $pathSuratKuasa = null;
            if ($request->hasFile('surat_kuasa')) {
                // Simpan path ke folder public/dokumen_keberatan
                $pathSuratKuasa = $request->file('surat_kuasa')->store('dokumen_keberatan', 'public');
            }

            // 3. Simpan ke Database
            $keberatan = new KeberatanInformasi();

            // RELASI UTAMA: Menyimpan ID, bukan string No Registrasi
            $keberatan->permohonan_informasi_id = $request->permohonan_informasi_id;

            // Data Pemohon
            $keberatan->nik_pemohon = $request->nik_pemohon;
            $keberatan->nama_pemohon = $request->nama_pemohon;
            $keberatan->pekerjaan = $request->pekerjaan;
            $keberatan->alamat_pemohon = $request->alamat_pemohon;
            $keberatan->telepon_pemohon = $request->telepon_pemohon; // Pastikan kolom ini ada di migration

            // Data Kuasa
            if ($request->has('is_dikuasakan')) {
                $keberatan->nama_kuasa = $request->nama_kuasa;
                $keberatan->alamat_kuasa = $request->alamat_kuasa;
                $keberatan->telepon_kuasa = $request->telepon_kuasa;
                $keberatan->surat_kuasa = $pathSuratKuasa; // Kolom di migration: surat_kuasa
            }

            // Data Kasus & Alasan
            // Laravel otomatis mengubah array ke JSON karena di Model sudah ada casts => array
            $keberatan->alasan_keberatan = $request->alasan_keberatan;

            // Kolom di migration: tujuan_penggunaan_informasi
            $keberatan->tujuan_penggunaan_informasi = $request->tujuan_penggunaan_informasi;

            $keberatan->status = 'diproses';
            $keberatan->save();

            DB::commit();

            return redirect()->route('keberatan-informasi')
                ->with('success', 'Keberatan berhasil diajukan. Kami akan segera memprosesnya.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($pathSuratKuasa && Storage::disk('public')->exists($pathSuratKuasa)) {
                Storage::disk('public')->delete($pathSuratKuasa);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }
}