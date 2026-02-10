<?php

namespace App\Http\Controllers\DaftarInformasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Informasi;
use App\Models\PerangkatDaerah; // Pastikan Model ini di-use
use App\Models\KategoriInformasi;
use App\Models\KategoriJenisInformasi;
use App\Models\KlasifikasiInformasi;
use Illuminate\Support\Facades\DB;

class DaftarInformasiPemprovController extends Controller
{
    /**
     * Menampilkan data Informasi berdasarkan logika hierarki Perangkat Daerah.
     * Logika: Kategori ID 1 -> Cari Perangkat Daerah (Parent NULL) -> Ambil ID-nya -> Tampilkan Informasi dari ID tersebut.
     */
    public function index(Request $request, $tahun = null)
    {
        // A. tentukan kategori informasi
        $targetKategoriId = 1;

        // B. cari kategori informasi yang sama 
        $opdTargetList = PerangkatDaerah::where('kategori_informasi_id', $targetKategoriId)
            ->whereNull('parent_id')
            ->orderBy('nama_perangkat_daerah', 'asc')
            ->get();

        // C. Ambil Array ID dari hasil pencarian di atas (Contoh hasil: [1, 5, 10])
        $targetOpdIds = $opdTargetList->pluck('id')->toArray();


        // Jika tahun tidak ada di URL, ambil tahun sekarang
        $currentYear = $tahun ?? date('Y');

        // Merge request agar filter form di view tetap terisi
        $request->merge([
            'tahun' => $currentYear,
        ]);


        $query = Informasi::query()
            ->with(['perangkatDaerah', 'klasifikasiInformasi', 'kategoriJenisInformasi']);

        // --- TERAPKAN LOGIKA PERMINTAAN ANDA DISINI ---
        // "Cari informasi dengan id perangkat daerah yang sudah dihasilkan"
        $query->whereIn('perangkat_daerah_id', $targetOpdIds);

        // Filter Tahun
        $query->where('tahun', $currentYear);

        // Filter Tambahan dari User (Search Box / Dropdown)

        // a. Jika user memilih filter Perangkat Daerah spesifik
        $query->when($request->filled('perangkat_daerah_id'), function ($q) use ($request) {
            $q->where('perangkat_daerah_id', $request->perangkat_daerah_id);
        });

        // b. Filter Keyword Judul
        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('judul_informasi', 'like', '%' . $request->keyword . '%');
        });

        // c. Filter Jenis Informasi
        $query->when($request->filled('kategori_jenis_informasi_id'), function ($q) use ($request) {
            $q->where('kategori_jenis_informasi_id', $request->kategori_jenis_informasi_id);
        });

        // d. Filter Klasifikasi
        $query->when($request->filled('klasifikasi_informasi_id'), function ($q) use ($request) {
            $q->where('klasifikasi_informasi_id', $request->klasifikasi_informasi_id);
        });

        // Eksekusi Query
        $informasis = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();


        // List Tahun untuk dropdown
        $tahunList = Informasi::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Data Penunjang Dropdown
        $kategoriList = KategoriJenisInformasi::select('id', 'nama_kategori')->get();
        $klasifikasilist = KlasifikasiInformasi::select('id', 'nama_klasifikasi')->get();

        // Ambil Nama Kategori Utama untuk Label View
        $namaKategoriUtama = KategoriInformasi::find($targetKategoriId)->nama_kategori ?? 'Pemerintah Daerah';

        // Informasi Terbaru (Side Widget) - Menggunakan Logika ID OPD yang sama agar konsisten
        $informasiTerbaru = Informasi::whereIn('perangkat_daerah_id', $targetOpdIds)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // DATA CHART - Statistik berdasarkan logika ID OPD yang sama
        $statsRaw = Informasi::select('klasifikasi_informasi_id', DB::raw('count(*) as total'))
            ->whereIn('perangkat_daerah_id', $targetOpdIds) // Konsisten dengan logika utama
            ->where('tahun', $currentYear) // Filter tahun agar chart sesuai tabel
            ->groupBy('klasifikasi_informasi_id')
            ->pluck('total', 'klasifikasi_informasi_id');

        $chartData = ['labels' => [], 'series' => []];
        foreach ($klasifikasilist as $k) {
            $chartData['labels'][] = $k->nama_klasifikasi; // Pastikan nama kolom di DB benar (nama_klasifikasi)
            $chartData['series'][] = $statsRaw[$k->id] ?? 0;
        }

        return view('content.daftar-informasi.daftar-informasi-pemprov', compact(
            'informasis',
            'tahunList',
            'kategoriList',
            'klasifikasilist',
            'informasiTerbaru',
            'chartData',
            'namaKategoriUtama',
            'opdTargetList' // Dikirim untuk isi dropdown filter OPD
        ))->with([
                    'isLocked' => true,
                    'lockedYear' => $currentYear,
                    'lockedKategoriId' => $targetKategoriId
                ]);
    }

    public function instansiPemprov(Request $request)
    {
        // 1. Query Dasar: Ambil Pemprov (ID 1) yang memiliki Parent (Sub-unit/UPT)
        $query = PerangkatDaerah::where('kategori_informasi_id', 1)
            ->whereNotNull('parent_id');

        // 2. Logika Pencarian Server-Side
        if ($request->filled('search')) {
            $query->where('nama_perangkat_daerah', 'like', '%' . $request->search . '%');
        }

        // 3. Urutkan dan Ambil Data
        $perangkatDaerahPemprov = $query->orderBy('nama_perangkat_daerah', 'asc')->get();

        // 4. Return View dengan data dan kata kunci pencarian (agar input tidak hilang saat reload)
        return view('content.instansi-pemerintah.instansi-pemprov', compact('perangkatDaerahPemprov'));
    }

    public function pemprovList(Request $request, $slug)
    {
        // 1. CARI PERANGKAT DAERAH (OPD) BERDASARKAN SLUG
        $opd = PerangkatDaerah::where('slug', $slug)->firstOrFail();

        $perangkatDaerahPemprov = PerangkatDaerah::where('kategori_informasi_id', 1)
            ->whereNotNull('parent_id')
            ->orderBy('nama_perangkat_daerah', 'asc')
            ->get();

        // 2. SETUP REQUEST (MERGE)
        $request->merge([
            'opd' => $opd->id,
            'tahun' => $request->get('tahun', date('Y')) // Ambil tahun dari URL atau Default tahun ini
        ]);

        // 3. QUERY UTAMA
        $query = Informasi::query()
            ->with(['perangkatDaerah', 'klasifikasiInformasi', 'kategoriJenisInformasi'])
            ->where('perangkat_daerah_id', $opd->id); // Filter Wajib: Hanya data OPD ini

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

        // 4. DATA PENDUKUNG VIEW (WAJIB ADA AGAR VIEW GLOBAL TIDAK ERROR)

        // A. List Tahun
        $tahunList = Informasi::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // B. List OPD (PENTING: Ini yang menyebabkan error sebelumnya jika tidak ada)
        $opdList = KategoriInformasi::with([
            'perangkatDaerahs' => function ($q) {
                $q->whereNull('parent_id')->with('children')->orderBy('nama_perangkat_daerah', 'asc');
            }
        ])->get();

        $kategoriList = KategoriJenisInformasi::all();
        $klasifikasilist = KlasifikasiInformasi::all();

        // C. Variable Nama untuk Label (Fallback)
        $namaKategoriUtama = $opd->nama_perangkat_daerah;

        // 5. DATA DASHBOARD (Chart & Sidebar) - Filter Spesifik OPD
        $informasiTerbaru = Informasi::where('perangkat_daerah_id', $opd->id)
            ->orderBy('created_at', 'desc')->take(5)->get();

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
        return view('content.daftar-informasi.daftar-informasi-pemprov', compact(
            'informasis',
            'tahunList',
            'opdList',
            'kategoriList',
            'klasifikasilist',
            'informasiTerbaru',
            'chartData',
            'namaKategoriUtama',
            'perangkatDaerahPemprov'
        ))->with([
                    'lockedOpd' => $opd // Mengirim objek OPD spesifik
                ]);
    }
}