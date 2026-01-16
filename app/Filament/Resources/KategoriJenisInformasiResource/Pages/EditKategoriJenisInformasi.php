<?php

namespace App\Filament\Resources\KategoriJenisInformasiResource\Pages;

use App\Filament\Resources\KategoriJenisInformasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriJenisInformasi extends EditRecord
{
    protected static string $resource = KategoriJenisInformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
