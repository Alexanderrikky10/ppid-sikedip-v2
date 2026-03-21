<?php

namespace App\Filament\Widgets;

use App\Models\Visitors;
use Doctrine\DBAL\SQL\Parser\Visitor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitorWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $jumlahPengunjung = Visitors::count();
        return [
            Stat::make('Jumlah Pengunjung', $jumlahPengunjung),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Average time on page', '3:12'),
        ];
    }
}
