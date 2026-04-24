<?php

namespace App\Filament\Resources\PermohonanInformasiResource\Widgets;

use App\Models\PermohonanInformasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PermohonanInformasiStatusStats extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalPermohonan = PermohonanInformasi::count();
        $sedangDiproses = PermohonanInformasi::where('status', 'proses')->count();
        $disetujui = PermohonanInformasi::where('status', 'disetujui')->count();
        $ditolak = PermohonanInformasi::where('status', 'ditolak')->count();
        $menunggu = PermohonanInformasi::where('status', 'menunggu')->count();

        return [
            Stat::make('📋 Total Permohonan', $this->formatNumber($totalPermohonan))
                ->description('Semua permohonan informasi masuk')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->chart($this->getChartData('all'))
                ->color('primary'),

            Stat::make('⏳ Sedang Diproses', $this->formatNumber($sedangDiproses))
                ->description('Permohonan sedang dalam proses')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->chart($this->getChartData('proses'))
                ->color('warning'),

            Stat::make('✅ Disetujui', $this->formatNumber($disetujui))
                ->description('Permohonan telah disetujui')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart($this->getChartData('disetujui'))
                ->color('success'),

            Stat::make('❌ Ditolak', $this->formatNumber($ditolak))
                ->description('Permohonan tidak dapat diproses')
                ->descriptionIcon('heroicon-m-x-circle')
                ->chart($this->getChartData('ditolak'))
                ->color('danger'),

            Stat::make('🕐 Menunggu', $this->formatNumber($menunggu))
                ->description('Permohonan belum diproses')
                ->descriptionIcon('heroicon-m-clock')
                ->chart($this->getChartData('menunggu'))
                ->color('gray'),

            Stat::make('📊 Tingkat Persetujuan', $this->getApprovalRate($totalPermohonan, $disetujui))
                ->description('Rasio permohonan disetujui')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->chart($this->getApprovalChart($totalPermohonan, $disetujui))
                ->color('info'),
        ];
    }

    /**
     * Format angka besar menjadi lebih ringkas
     */
    private function formatNumber(int $number): string
    {
        return match (true) {
            $number >= 1_000_000 => number_format($number / 1_000_000, 1) . 'M',
            $number >= 1_000 => number_format($number / 1_000, 1) . 'K',
            default => (string) $number,
        };
    }

    /**
     * Hitung tingkat persetujuan dalam persen
     */
    private function getApprovalRate(int $total, int $approved): string
    {
        if ($total === 0)
            return '0%';

        $rate = ($approved / $total) * 100;

        return number_format($rate, 1) . '%';
    }

    /**
     * Data chart mini per status (7 hari terakhir)
     */
    private function getChartData(string $status): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $query = PermohonanInformasi::whereDate(
                'created_at',
                now()->subDays($i)->toDateString()
            );

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            $data[] = $query->count();
        }

        return $data;
    }

    /**
     * Chart untuk tingkat persetujuan
     */
    private function getApprovalChart(int $total, int $approved): array
    {
        if ($total === 0)
            return [0, 0, 0, 0, 0, 0, 0];

        $rate = ($approved / $total) * 100;

        // Simulasi trend chart
        return [
            max(0, $rate - 10),
            max(0, $rate - 7),
            max(0, $rate - 5),
            max(0, $rate - 3),
            max(0, $rate - 1),
            $rate,
            $rate,
        ];
    }
}