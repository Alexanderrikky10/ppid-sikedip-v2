<?php

namespace App\Filament\Staff\Resources\KeberatanInformasiResource\Pages;

use App\Filament\Staff\Resources\KeberatanInformasiResource;
use App\Filament\Staff\Resources\KeberatanInformasiResource\Widgets\KeberatanInformasiStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeberatanInformasis extends ListRecords
{
    protected static string $resource = KeberatanInformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            KeberatanInformasiStats::class,
        ];
    }
}
