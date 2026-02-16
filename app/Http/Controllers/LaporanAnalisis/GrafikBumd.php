<?php

namespace App\Http\Controllers\LaporanAnalisis;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDaerah;
use Illuminate\Http\Request;

class GrafikBumd extends Controller
{
    public function bumdGrafik(Request $request)
    {
        // Ambil input tahun, default 2023 sampai 2026
        $dariTahun = $request->input('dari_tahun', 2017);
        $sampaiTahun = $request->input('sampai_tahun', 2026);

        // Buat array rentang tahun
        $selectedYears = range(min($dariTahun, $sampaiTahun), max($dariTahun, $sampaiTahun));

        $perangkatDaerahs = PerangkatDaerah::where('kategori_informasi_id', 3)
            ->with([
                'informasis' => function ($query) use ($selectedYears) {
                    $query->whereIn('tahun', $selectedYears);
                }
            ])
            ->get();

        $labels = $perangkatDaerahs->pluck('nama_perangkat_daerah');

        $colorPalette = [
            2026 => '#10B981',
            2025 => '#374151',
            2024 => '#94A3B8',
            2023 => '#3B82F6',
            2022 => '#8B5CF6',
            2021 => '#F59E0B'
        ];

        $datasets = [];
        foreach (array_reverse($selectedYears) as $year) {
            $datasets[] = [
                'label' => (string) $year,
                'data' => $perangkatDaerahs->map(fn($opd) => $opd->informasis->where('tahun', $year)->count()),
                'backgroundColor' => $colorPalette[$year] ?? '#CBD5E1',
                'borderRadius' => 6,
                'barPercentage' => 0.8,
            ];
        }

        return view('content.laporan-analisis.grafik-bumd', compact('labels', 'datasets', 'dariTahun', 'sampaiTahun'));
    }
}