<?php

namespace App\Filament\Resources\KlasifikasiInformasiResource\Pages;

use App\Filament\Resources\KlasifikasiInformasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKlasifikasiInformasi extends EditRecord
{
    protected static string $resource = KlasifikasiInformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
