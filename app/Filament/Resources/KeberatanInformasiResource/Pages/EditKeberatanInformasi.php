<?php

namespace App\Filament\Resources\KeberatanInformasiResource\Pages;

use App\Filament\Resources\KeberatanInformasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeberatanInformasi extends EditRecord
{
    protected static string $resource = KeberatanInformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
