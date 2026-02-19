<?php

namespace App\Filament\Resources\PanduanPenyusunanDipResource\Pages;

use App\Filament\Resources\PanduanPenyusunanDipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPanduanPenyusunanDips extends ListRecords
{
    protected static string $resource = PanduanPenyusunanDipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
