<?php

namespace App\Filament\Resources\KlasifikasiInformasiResource\Pages;

use App\Filament\Resources\KlasifikasiInformasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKlasifikasiInformasis extends ListRecords
{
    protected static string $resource = KlasifikasiInformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
