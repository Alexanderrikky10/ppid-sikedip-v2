<?php

namespace App\Filament\Staff\Resources\KategoriJenisInformasiResource\Pages;

use App\Filament\Staff\Resources\KategoriJenisInformasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriJenisInformasis extends ListRecords
{
    protected static string $resource = KategoriJenisInformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
