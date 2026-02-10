<?php

namespace App\Http\Controllers\DaftarInformasi;

use App\Http\Controllers\Controller;
use App\Models\KategoriJenisInformasi;
use App\Models\KlasifikasiInformasi;
use Illuminate\Http\Request;
use App\Models\Informasi;
use App\Models\KategoriInformasi;
use Illuminate\Support\Facades\DB;

class DaftarInformasiController extends Controller
{
    public function index(Request $request)
    {
        // 1. DATA UNTUK DROPDOWN FILTER
        $tahunList = Informasi::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // Logic Filter OPD Hierarki (Eager Load 'children' untuk hindari N+1 di Dropdown)
        $opdList = KategoriInformasi::with([
            'perangkatDaerahs' => function ($q) {
                $q->whereNull('parent_id')
                    ->with('children') // <--- PENTING: Mencegah N+1 saat looping sub-OPD
                    ->orderBy('nama_perangkat_daerah', 'asc');
            }
        ])->get();

        $kategoriList = KategoriJenisInformasi::all();
        $klasifikasilist = KlasifikasiInformasi::all();

        // 2. DATA DASHBOARD (STATISTIK & TERBARU)
        $informasiTerbaru = Informasi::with(['perangkatDaerah', 'klasifikasiInformasi']) // <--- Mencegah N+1 di Card Terbaru
            ->orderBy('created_at', 'desc')->take(5)->get();

        $statsRaw = Informasi::select('klasifikasi_informasi_id', DB::raw('count(*) as total'))
            ->groupBy('klasifikasi_informasi_id')->pluck('total', 'klasifikasi_informasi_id');

        $chartData = ['labels' => [], 'series' => []];
        foreach ($klasifikasilist as $k) {
            $chartData['labels'][] = $k->nama_klasifikasi;
            $chartData['series'][] = $statsRaw[$k->id] ?? 0;
        }

        // 3. QUERY UTAMA (TABEL) - SOLUSI ANTI N+1
        $query = Informasi::query()->with([
            'kategoriInformasi',      // Relasi ke tabel kategori_informasis
            'kategoriJenisInformasi', // Relasi ke tabel kategori_jenis_informasis
            'klasifikasiInformasi',   // Relasi ke tabel klasifikasi_informasis
            'perangkatDaerah'         // Relasi ke tabel perangkat_daerahs
        ]);

        // Filter Logic
        $query->when($request->filled('keyword'), fn($q) => $q->where('judul_informasi', 'like', '%' . $request->keyword . '%'));
        $query->when($request->filled('tahun'), fn($q) => $q->where('tahun', $request->tahun));
        $query->when($request->filled('opd'), fn($q) => $q->where('perangkat_daerah_id', $request->opd)); // Gunakan ID
        $query->when($request->filled('kategori_jenis_informasi_id'), fn($q) => $q->where('kategori_jenis_informasi_id', $request->kategori_jenis_informasi_id));
        $query->when($request->filled('klasifikasi_informasi_id'), fn($q) => $q->where('klasifikasi_informasi_id', $request->klasifikasi_informasi_id));

        // Sorting (Pemprov ID 1 Prioritas)
        $informasis = $query->orderByRaw("CASE WHEN kategori_informasi_id = 1 THEN 0 ELSE 1 END")
            ->orderBy('tahun', 'desc')->orderBy('created_at', 'desc')
            ->paginate(10)->withQueryString();

        return view('content.daftar-informasi.daftar-informasi-publik', compact(
            'informasis',
            'tahunList',
            'opdList',
            'kategoriList',
            'klasifikasilist',
            'informasiTerbaru',
            'chartData'
        ));
    }


}