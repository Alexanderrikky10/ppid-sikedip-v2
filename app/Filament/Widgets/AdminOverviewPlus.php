<?php

namespace App\Filament\Widgets;

use App\Models\Visitors;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverviewPlus extends BaseWidget
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

        $jumlahPengunjung = Visitors::count();
        return [
            //
            Stat::make('Total Publish Data', "{$this->formatNumber(1500)} Data")->icon('heroicon-o-circle-stack'),
            Stat::make('Total Download', "{$this->formatNumber(1300)} Download")->icon('heroicon-o-arrow-down-tray'),
            Stat::make('Jumlah Pengunjung', "{$this->formatNumber($jumlahPengunjung)} Pengunjung")->icon('heroicon-o-user-group'),
            Stat::make('Total Publish Data', "{$this->formatNumber(1500)} Data")->icon('heroicon-o-circle-stack'),
            Stat::make('Total Download', "{$this->formatNumber(1300)} Download")->icon('heroicon-o-arrow-down-tray'),
            Stat::make('Jumlah Pengunjung', "{$this->formatNumber($jumlahPengunjung)} Pengunjung")->icon('heroicon-o-user-group'),
        ];
    }
}
