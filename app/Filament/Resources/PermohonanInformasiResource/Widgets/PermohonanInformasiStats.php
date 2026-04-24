<?php

namespace App\Filament\Resources\PermohonanInformasiResource\Widgets;

use App\Models\PermohonanInformasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PermohonanInformasiStats extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        // ── Hitung Data Waktu ─────────────────────────────────────
        $hariIni = PermohonanInformasi::whereDate('created_at', Carbon::today())->count();
        $kemarin = PermohonanInformasi::whereDate('created_at', Carbon::yesterday())->count();

        $mingguIni = PermohonanInformasi::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ])->count();

        $mingguLalu = PermohonanInformasi::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek(),
        ])->count();

        $bulanIni = PermohonanInformasi::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $bulanLalu = PermohonanInformasi::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $totalKeseluruhan = PermohonanInformasi::count();

        // ── Hitung Data Status ────────────────────────────────────
        $ditolak = PermohonanInformasi::where('status', 'ditolak')->count();
        $disetujui = PermohonanInformasi::where('status', 'disetujui')->count();

        // ── Hitung Tren ───────────────────────────────────────────
        $trendHarian = $this->hitungTrend($hariIni, $kemarin);
        $trendMingguan = $this->hitungTrend($mingguIni, $mingguLalu);
        $trendBulanan = $this->hitungTrend($bulanIni, $bulanLalu);

        return [
            // ── Stat 1: Hari Ini ──────────────────────────────────
            Stat::make('Permohonan Hari Ini', $this->formatNumber($hariIni))
                ->description($trendHarian['label'] . ' dibanding kemarin (' . $kemarin . ')')
                ->descriptionIcon($trendHarian['icon'])
                ->chart($this->chartHarian())
                ->color($trendHarian['color']),

            // ── Stat 2: Minggu Ini ────────────────────────────────
            Stat::make('Permohonan Minggu Ini', $this->formatNumber($mingguIni))
                ->description($trendMingguan['label'] . ' dibanding minggu lalu (' . $mingguLalu . ')')
                ->descriptionIcon($trendMingguan['icon'])
                ->chart($this->chartMingguan())
                ->color($trendMingguan['color']),

            // ── Stat 3: Bulan Ini ─────────────────────────────────
            Stat::make('Permohonan Bulan Ini', $this->formatNumber($bulanIni))
                ->description($trendBulanan['label'] . ' dibanding bulan lalu (' . $bulanLalu . ')')
                ->descriptionIcon($trendBulanan['icon'])
                ->chart($this->chartBulanan())
                ->color($trendBulanan['color']),

            // ── Stat 4: Total Keseluruhan ─────────────────────────
            Stat::make('Total Keseluruhan', $this->formatNumber($totalKeseluruhan))
                ->description('Semua permohonan informasi tercatat')
                ->descriptionIcon('heroicon-m-archive-box')
                ->chart($this->chartTotal())
                ->color('primary'),

            // ── Stat 5: Ditolak ───────────────────────────────────
            Stat::make('❌ Ditolak', $this->formatNumber($ditolak))
                ->description('Permohonan tidak dapat diproses')
                ->descriptionIcon('heroicon-m-x-circle')
                ->chart($this->getChartData('ditolak'))
                ->color('danger'),

            // ── Stat 6: Disetujui ─────────────────────────────────
            Stat::make('✅ Disetujui', $this->formatNumber($disetujui))
                ->description('Permohonan telah disetujui')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart($this->getChartData('disetujui'))
                ->color('success'),
        ];
    }

    // ── Helper: Format Angka ──────────────────────────────────────
    private function formatNumber(int $number): string
    {
        return match (true) {
            $number >= 1_000_000 => number_format($number / 1_000_000, 1) . 'M',
            $number >= 1_000 => number_format($number / 1_000, 1) . 'K',
            default => (string) $number,
        };
    }

    // ── Helper: Hitung Tren ───────────────────────────────────────
    private function hitungTrend(int $sekarang, int $sebelumnya): array
    {
        if ($sekarang > $sebelumnya) {
            $selisih = $sekarang - $sebelumnya;
            return [
                'label' => "Naik {$selisih}",
                'icon' => 'heroicon-m-arrow-trending-up',
                'color' => 'success',
            ];
        }

        if ($sekarang < $sebelumnya) {
            $selisih = $sebelumnya - $sekarang;
            return [
                'label' => "Turun {$selisih}",
                'icon' => 'heroicon-m-arrow-trending-down',
                'color' => 'danger',
            ];
        }

        return [
            'label' => 'Sama',
            'icon' => 'heroicon-m-minus',
            'color' => 'warning',
        ];
    }

    // ── Chart: 7 Hari Terakhir ────────────────────────────────────
    private function chartHarian(): array
    {
        return collect(range(6, 0))->map(
            fn($i) => PermohonanInformasi::whereDate('created_at', Carbon::today()->subDays($i))->count()
        )->toArray();
    }

    // ── Chart: 7 Minggu Terakhir ──────────────────────────────────
    private function chartMingguan(): array
    {
        return collect(range(6, 0))->map(
            fn($i) => PermohonanInformasi::whereBetween('created_at', [
                Carbon::now()->subWeeks($i)->startOfWeek(),
                Carbon::now()->subWeeks($i)->endOfWeek(),
            ])->count()
        )->toArray();
    }

    // ── Chart: 7 Bulan Terakhir ───────────────────────────────────
    private function chartBulanan(): array
    {
        return collect(range(6, 0))->map(
            fn($i) => PermohonanInformasi::whereMonth('created_at', Carbon::now()->subMonths($i)->month)
                ->whereYear('created_at', Carbon::now()->subMonths($i)->year)
                ->count()
        )->toArray();
    }

    // ── Chart: Akumulasi Total per Bulan ──────────────────────────
    private function chartTotal(): array
    {
        return collect(range(6, 0))->map(
            fn($i) => PermohonanInformasi::where(
                'created_at',
                '<=',
                Carbon::now()->subMonths($i)->endOfMonth()
            )->count()
        )->toArray();
    }

    // ── Chart: Data per Status (7 Hari Terakhir) ─────────────────
    private function getChartData(string $status): array
    {
        return collect(range(6, 0))->map(
            fn($i) => PermohonanInformasi::where('status', $status)
                ->whereDate('created_at', Carbon::today()->subDays($i))
                ->count()
        )->toArray();
    }
}