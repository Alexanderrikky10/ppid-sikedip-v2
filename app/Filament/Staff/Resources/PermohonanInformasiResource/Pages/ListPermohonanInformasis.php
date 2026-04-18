<?php

namespace App\Filament\Staff\Resources\PermohonanInformasiResource\Pages;

use App\Filament\Staff\Resources\PermohonanInformasiResource;
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
}
