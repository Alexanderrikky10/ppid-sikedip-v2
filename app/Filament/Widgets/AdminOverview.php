<?php

namespace App\Filament\Widgets;

use App\Models\Informasi;
use App\Models\PerangkatDaerah;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverview extends BaseWidget
{

    protected int|string|array $columnSpan = 'full';
    private function formatNumber($number)
    {
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M'; // contoh: 1.2M
        }
        if ($number >= 1000) {
            return number_format($number / 1000, 1) . 'k'; // contoh: 192.1k
        }

        return (string) $number;
    }

    protected function getStats(): array
    {

        $TotalPerangkatDaerah = PerangkatDaerah::count();
        $totalDaftarInformasi = Informasi::count();
        $totalDownload = Informasi::sum('downloads_count');
        $totalPengunjung = Informasi::sum('views_count');


        return [
            //
            Stat::make('Total Publish Opd', "{$this->formatNumber($TotalPerangkatDaerah)} OPD")
                ->icon('heroicon-o-building-office')
                ->chart([17, 16, 14, 15, 14, 13, 18])
                ->color('success')
            ,
            Stat::make('Total publis Data', "{$this->formatNumber($totalDaftarInformasi)} DIP")
                ->icon('heroicon-o-circle-stack')
                ->chart([17, 16, 14, 15, 14, 13, 16])
                ->color('warning'),
            Stat::make('Total Download', "{$this->formatNumber($totalDownload)} Informasi")
                ->icon('heroicon-o-arrow-down-tray')
                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->color('primary'),
            Stat::make('Total Pengunjung', "{$this->formatNumber(800)} Informasi")
                ->icon('heroicon-o-user-group')
                ->chart([17, 16, 14, 15, 14, 13, 20])
                ->color('success'),
        ];
    }
}
