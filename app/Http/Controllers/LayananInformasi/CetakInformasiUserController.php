<?php

namespace App\Http\Controllers\LayananInformasi;

use Illuminate\Http\Request;
use App\Models\KategoriInformasi;
use App\Http\Controllers\Controller;
use App\Models\KlasifikasiInformasi;
use App\Models\KategoriJenisInformasi;

class CetakInformasiUserController extends Controller
{
    //
    public function index()
    {
        // 1. Data Tahun (Dari tahun ini mundur ke 2017)
        $tahunList = range(date('Y'), 2017);

        // 2. Data Perangkat Daerah (Hierarki: Kategori -> Induk -> Anak)
        // Kita ambil KategoriInformasi beserta PerangkatDaerah yang terkait.
        $opdList = KategoriInformasi::with([
            'perangkatDaerahs' => function ($query) {
                // Ambil hanya Parent (Induk) terlebih dahulu dan urutkan
                $query->whereNull('parent_id')
                    ->with([
                        'children' => function ($subQuery) {
                        // Eager Load Anak (Sub-OPD) jika ada
                        $subQuery->orderBy('nama_perangkat_daerah', 'asc');
                    }
                    ])
                    ->orderBy('nama_perangkat_daerah', 'asc');
            }
        ])
            ->orderBy('id', 'asc') // Urutkan kategorinya (Provinsi, Kabupaten, dll)
            ->get();

        // 3. Data Pendukung Lainnya
        $kategoriList = KategoriJenisInformasi::all();
        $klasifikasilist = KlasifikasiInformasi::all();

        return view('content.layanan-informasi.cetak-informasi', compact(
            'tahunList',
            'opdList',
            'kategoriList',
            'klasifikasilist'
        ));
    }
}
