<?php

namespace App\Filament\Widgets;

use App\Models\Informasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class InformasiPemkabStats extends BaseWidget
{
    protected int $kategoriId = 2;
    protected static bool $isDiscovered = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Hari Ini', Informasi::where('kategori_informasi_id', $this->kategoriId)
                ->whereDate('created_at', Carbon::today())->count())
                ->description('Data baru hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Minggu Ini', Informasi::where('kategori_informasi_id', $this->kategoriId)
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count())
                ->icon('heroicon-o-calendar'),

            Stat::make('Bulan Ini', Informasi::where('kategori_informasi_id', $this->kategoriId)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)->count()),

            Stat::make('Total Tahun Ini', Informasi::where('kategori_informasi_id', $this->kategoriId)
                ->whereYear('created_at', Carbon::now()->year)->count())
                ->description('Total keseluruhan tahun ' . now()->year)
                ->color('info'),
        ];
    }
}
