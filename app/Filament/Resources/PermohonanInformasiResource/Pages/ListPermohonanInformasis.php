<?php

namespace App\Filament\Resources\PermohonanInformasiResource\Pages;

use App\Filament\Resources\PermohonanInformasiResource;
use App\Filament\Resources\PermohonanInformasiResource\Widgets\PermohonanInformasiStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermohonanInformasis extends ListRecords
{
    protected static string $resource = PermohonanInformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getHeaderWidgets(): array
    {
        return [
            PermohonanInformasiStats::class,
        ];
    }
}
