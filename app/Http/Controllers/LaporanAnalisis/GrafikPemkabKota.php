<?php

namespace App\Http\Controllers\LaporanAnalisis;

use Illuminate\Http\Request;
use App\Models\PerangkatDaerah;
use App\Http\Controllers\Controller;

class GrafikPemkabKota extends Controller
{
    //
    public function grafikPemkabKota(Request $request)
    {
        // 1. Tentukan tahun sekarang dan 5 tahun ke belakang secara dinamis
        $tahunSekarang = (int) date('Y');
        $defaultDariTahun = $tahunSekarang - 4; // Contoh: 2022 jika sekarang 2026

        $dariTahun = $request->input('dari_tahun', $defaultDariTahun);
        $sampaiTahun = $request->input('sampai_tahun', $tahunSekarang);

        // Buat array rentang tahun berdasarkan input
        $selectedYears = range(min($dariTahun, $sampaiTahun), max($dariTahun, $sampaiTahun));

        // 2. Ambil semua perangkat daerah kategori 3 (BUMD)
        $perangkatDaerahs = PerangkatDaerah::where('kategori_informasi_id', 2)
            ->with([
                'informasis' => function ($query) use ($selectedYears) {
                    $query->whereIn('tahun', $selectedYears);
                }
            ])
            ->get();

        $labels = $perangkatDaerahs->pluck('nama_perangkat_daerah');

        // 3. Tentukan palet warna dinamis (tanpa hardcode tahun)
        // Warna diurutkan dari tahun terbaru ke terlama
        $baseColors = [
            '#10B981', // Hijau (Terbaru)
            '#374151', // Hitam Abu
            '#3B82F6', // Biru
            '#8B5CF6', // Ungu
            '#F59E0B', // Orange
            '#94A3B8', // Abu-abu
        ];

        $datasets = [];
        // Urutkan tahun dari yang terbesar ke terkecil untuk tampilan legend/bar yang rapi
        $yearsForDataset = array_reverse($selectedYears);

        foreach ($yearsForDataset as $index => $year) {
            $datasets[] = [
                'label' => (string) $year,
                'data' => $perangkatDaerahs->map(fn($opd) => $opd->informasis->where('tahun', $year)->count()),
                // Ambil warna berdasarkan urutan index, jika habis gunakan warna default
                'backgroundColor' => $baseColors[$index] ?? '#CBD5E1',
                'borderRadius' => 6,
                'barPercentage' => 0.8,
            ];
        }

        return view('content.laporan-analisis.grafik-pemkabkota', compact('labels', 'datasets', 'dariTahun', 'sampaiTahun'));
    }
}
