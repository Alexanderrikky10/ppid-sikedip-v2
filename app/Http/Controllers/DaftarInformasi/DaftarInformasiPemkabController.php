<?php

namespace App\Http\Controllers\DaftarInformasi;

use App\Models\Informasi;
use Illuminate\Http\Request;
use App\Models\PerangkatDaerah;
use App\Models\KategoriInformasi;
use App\Http\Controllers\Controller;
use App\Models\KlasifikasiInformasi;
use App\Models\KategoriJenisInformasi;

class DaftarInformasiPemkabController extends Controller
{
    // tampilkan daftar pempkab utama 
    // File: app/Http/Controllers/DaftarInformasi/DaftarInformasiPemkabController.php

    public function index(Request $request)
    {
        // 1. Query Dasar: Ambil SEMUA data kategori 2 (Tanpa filter parent_id)
        $query = PerangkatDaerah::where('kategori_informasi_id', 2);

        // 2. Logika Pencarian Server-Side
        if ($request->filled('search')) {
            $query->where('nama_perangkat_daerah', 'like', '%' . $request->search . '%');
        }

        // 3. Urutkan dan Ambil Data
        $PemkabUtama = $query->orderBy('nama_perangkat_daerah', 'asc')->get();

        return view('content.instansi-pemerintah.instansi-pemkab', compact('PemkabUtama'));
    }


    public function pemkabList(Request $request, $slug)
    {
        // 1. CARI PERANGKAT DAERAH (OPD) YANG SEDANG AKTIF BERDASARKAN SLUG
        $opd = PerangkatDaerah::where('slug', $slug)->firstOrFail();

        // 2. AMBIL DAFTAR SEMUA PEMKAB (Untuk Grid/Navigasi di View)
        // Ini yang menyebabkan error "Undefined variable" sebelumnya
        $PemkabUtama = PerangkatDaerah::where('kategori_informasi_id', 2)
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

        // List OPD Dropdown (Jika diperlukan sidebar filter)
        $opdList = KategoriInformasi::with([
            'perangkatDaerahs' => function ($q) {
                $q->whereNull('parent_id')->with('children')->orderBy('nama_perangkat_daerah', 'asc');
            }
        ])->get();

        $kategoriList = KategoriJenisInformasi::select('id', 'nama_kategori')->get();
        $klasifikasilist = KlasifikasiInformasi::select('id', 'nama_klasifikasi')->get();

        // Nama Kategori Utama
        $namaKategoriUtama = $opd->nama_perangkat_daerah;

        // Informasi Terbaru (Side Widget)
        $informasiTerbaru = Informasi::where('perangkat_daerah_id', $opd->id)
            ->orderBy('created_at', 'desc')->take(5)->get();

        // Data Chart
        $statsRaw = Informasi::select('klasifikasi_informasi_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
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
        // Pastikan 'PemkabUtama' dimasukkan ke dalam compact
        return view('content.daftar-informasi.daftar-informasi-pemkab', compact(
            'informasis',
            'tahunList',
            'opdList',
            'kategoriList',
            'klasifikasilist',
            'informasiTerbaru',
            'chartData',
            'namaKategoriUtama',
            'PemkabUtama'
        ))->with([
                    'lockedOpd' => $opd
                ]);
    }
}
