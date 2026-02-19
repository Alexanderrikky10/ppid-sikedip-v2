<?php

namespace App\Filament\Resources\PanduanPenyusunanDipResource\Pages;

use App\Filament\Resources\PanduanPenyusunanDipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPanduanPenyusunanDip extends EditRecord
{
    protected static string $resource = PanduanPenyusunanDipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
