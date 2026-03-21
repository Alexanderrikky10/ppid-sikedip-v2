<?php

namespace App\Filament\Resources\SurveyKualitasResource\Pages;

use App\Filament\Resources\SurveyKualitasResource;
use App\Filament\Resources\SurveyKualitasResource\Widgets\FilterKualitas;
use App\Filament\Resources\SurveyKualitasResource\Widgets\HeaderPeriode;
use App\Filament\Resources\SurveyKualitasResource\Widgets\IndexKualitas;
use App\Filament\Resources\SurveyKualitasResource\Widgets\StatsOverviewKualitas;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyKualitas extends ListRecords
{
    protected static string $resource = SurveyKualitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Pertanyaan')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            HeaderPeriode::class,           // Header periode - full width (sort: 0)
            StatsOverviewKualitas::class,  // Stats 4 cards - kiri 2 kolom (sort: 1)
            FilterKualitas::class,          // Filter - kanan 1 kolom (sort: 2)
            IndexKualitas::class,           // Chart - full width (sort: 4)
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return [
            'sm' => 1,  // 1 kolom di small screen (mobile)
            'md' => 3,  // 3 kolom di medium screen
            'lg' => 3,  // 3 kolom di large screen
            'xl' => 3,  // 3 kolom di extra large screen
            '2xl' => 3, // 3 kolom di 2xl screen
        ];
    }
}
