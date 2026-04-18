<?php

namespace App\Filament\Resources\PerangkatDaerahResource\Widgets;

use App\Models\PerangkatDaerah;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PerangkatDaerahStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Kategori 1: Perangkat Daerah PEMPROV
            Stat::make('Pemprov', PerangkatDaerah::where('kategori_informasi_id', 1)->count())
                ->description('Total Perangkat Daerah Provinsi')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            // Kategori 2: Perangkat Daerah PEMKAB/KOTA
            Stat::make('Pemkab / Pemkot', PerangkatDaerah::where('kategori_informasi_id', 2)->count())
                ->description('Total Perangkat Daerah Kab/Kota')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('info'),

            // Kategori 3: BUMD
            Stat::make('BUMD', PerangkatDaerah::where('kategori_informasi_id', 3)->count())
                ->description('Total Badan Usaha Milik Daerah')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('success'),

            // Total Keseluruhan (Opsional)
            Stat::make('Total Instansi', PerangkatDaerah::count())
                ->description('Gabungan semua kategori')
                ->icon('heroicon-m-server')
                ->color('gray'),
        ];
    }
}