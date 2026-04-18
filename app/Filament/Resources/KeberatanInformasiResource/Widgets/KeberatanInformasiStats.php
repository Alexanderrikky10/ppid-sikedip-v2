<?php

namespace App\Filament\Resources\KeberatanInformasiResource\Widgets;

use App\Models\KeberatanInformasi; // Pastikan model ini sudah ada
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KeberatanInformasiStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Total semua data keberatan
            Stat::make('Total Keberatan', KeberatanInformasi::count())
                ->description('Jumlah total keberatan informasi')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            // Asumsi status: 'Proses', 'Pending', atau sejenisnya
            Stat::make('Keberatan Diproses', KeberatanInformasi::where('status', 'Proses')->count())
                ->description('Jumlah keberatan yang sedang diproses')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            // Asumsi status: 'Selesai'
            Stat::make('Keberatan Selesai', KeberatanInformasi::where('status', 'Selesai')->count())
                ->description('Jumlah keberatan yang telah selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            // Asumsi status: 'Ditolak'
            Stat::make('Keberatan Ditolak', KeberatanInformasi::where('status', 'Ditolak')->count())
                ->description('Jumlah keberatan yang ditolak')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}