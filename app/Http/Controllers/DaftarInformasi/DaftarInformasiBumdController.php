<?php

namespace App\Http\Controllers\DaftarInformasi;

use App\Models\Informasi;
use Illuminate\Http\Request;
use App\Models\PerangkatDaerah;
use App\Models\KategoriInformasi;
use App\Http\Controllers\Controller;
use App\Models\KlasifikasiInformasi;
use App\Models\KategoriJenisInformasi;
use Illuminate\Support\Facades\DB;

class DaftarInformasiBumdController extends Controller
{
    /**
     * Menampilkan Daftar BUMD (Halaman Grid)
     */
    public function index(Request $request)
    {
        // 1. Query Dasar: Ambil SEMUA data kategori 3 (BUMD)
        $query = PerangkatDaerah::where('kategori_informasi_id', 3);

        // 2. Logika Pencarian Server-Side
        if ($request->filled('search')) {
            $query->where('nama_perangkat_daerah', 'like', '%' . $request->search . '%');
        }

        // 3. Urutkan dan Ambil Data
        // PERUBAHAN: Nama variabel diganti dari $PemkabUtama menjadi $BumdUtama
        $BumdUtama = $query->orderBy('nama_perangkat_daerah', 'asc')->get();

        // 4. Return View
        return view('content.instansi-pemerintah.instansi-bumd', compact('BumdUtama'));
    }

    /**
     * Menampilkan Detail Informasi dari BUMD yang dipilih
     */
    public function bumdList(Request $request, $slug)
    {
        // 1. CARI BUMD YANG SEDANG AKTIF BERDASARKAN SLUG
        $opd = PerangkatDaerah::where('slug', $slug)->firstOrFail();

        // 2. AMBIL DAFTAR SEMUA BUMD (Untuk Grid/Navigasi di View Bawah)
        // Kategori ID 3 = BUMD
        $BumdUtama = PerangkatDaerah::where('kategori_informasi_id', 3)
            ->orderBy('nama_perangkat_daerah', 'asc')
            ->get();
        // --------------------------

        // 3. SETUP REQUEST (MERGE)
        $request->merge([
            'opd' => $opd->id,
            'tahun' => $request->get('tahun', date('Y'))
        ]);

        // 4. QUERY UTAMA (Daftar Dokumen Informasi)
        $query = Informasi::query()
            ->with(['perangkatDaerah', 'klasifikasiInformasi', 'kategoriJenisInformasi'])
            ->where('perangkat_daerah_id', $opd->id);

        // Filter Tahun
        $query->when($request->filled('tahun'), function ($q) use ($request) {
            $q->where('tahun', $request->tahun);
        });

        // Filter Keyword
        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('judul_informasi', 'like', '%' . $request->keyword . '%');
        });

        // Filter Lainnya
        $query->when($request->filled('kategori_jenis_informasi_id'), fn($q) => $q->where('kategori_jenis_informasi_id', $request->kategori_jenis_informasi_id));
        $query->when($request->filled('klasifikasi_informasi_id'), fn($q) => $q->where('klasifikasi_informasi_id', $request->klasifikasi_informasi_id));

        $informasis = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // 5. DATA PENDUKUNG VIEW
        $tahunList = Informasi::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // List OPD Dropdown (Optional if needed)
        $opdList = KategoriInformasi::with([
            'perangkatDaerahs' => function ($q) {
                $q->where('kategori_informasi_id', 3) // Specific for BUMD
                    ->with('children')
                    ->orderBy('nama_perangkat_daerah', 'asc');
            }
        ])->where('id', 3)->get();

        $kategoriList = KategoriJenisInformasi::select('id', 'nama_kategori')->get();
        $klasifikasilist = KlasifikasiInformasi::select('id', 'nama_klasifikasi')->get();

        // Nama Kategori Utama
        $namaKategoriUtama = $opd->nama_perangkat_daerah;

        // Informasi Terbaru (Side Widget)
        $informasiTerbaru = Informasi::where('perangkat_daerah_id', $opd->id)
            ->orderBy('created_at', 'desc')->take(5)->get();

        // Data Chart
        $statsRaw = Informasi::select('klasifikasi_informasi_id', DB::raw('count(*) as total'))
            ->where('perangkat_daerah_id', $opd->id)
            ->when($request->filled('tahun'), fn($q) => $q->where('tahun', $request->tahun))
            ->groupBy('klasifikasi_informasi_id')
            ->pluck('total', 'klasifikasi_informasi_id');

        $chartData = ['labels' => [], 'series' => []];
        foreach ($klasifikasilist as $k) {
            $chartData['labels'][] = $k->nama_klasifikasi;
            $chartData['series'][] = $statsRaw[$k->id] ?? 0;
        }

        // 6. RETURN VIEW
        // We pass '$BumdUtama' instead of '$PemkabUtama'
        return view('content.daftar-informasi.daftar-informasi-bumd', compact(
            'informasis',
            'tahunList',
            'opdList',
            'kategoriList',
            'klasifikasilist',
            'informasiTerbaru',
            'chartData',
            'namaKategoriUtama',
            'BumdUtama'
        ))->with([
                    'lockedOpd' => $opd
                ]);
    }
}