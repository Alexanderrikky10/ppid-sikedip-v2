<?php

namespace App\Filament\Resources\PermohonanInformasiResource\Widgets;

use App\Models\PermohonanInformasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class PermohonanInformasiStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Query langsung ke model tanpa filter kategori
        return [
            Stat::make('Hari Ini', PermohonanInformasi::whereDate('created_at', Carbon::today())->count())
                ->description('Permohonan masuk hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Minggu Ini', PermohonanInformasi::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count())
                ->icon('heroicon-o-calendar'),

            Stat::make('Bulan Ini', PermohonanInformasi::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count()),

            Stat::make('Total Keseluruhan', PermohonanInformasi::count())
                ->description('Semua data permohonan')
                ->color('info'),
        ];
    }
}