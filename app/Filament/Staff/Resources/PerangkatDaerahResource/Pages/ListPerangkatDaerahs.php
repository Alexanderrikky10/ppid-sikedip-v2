<?php

namespace App\Filament\Staff\Resources\PerangkatDaerahResource\Pages;

use App\Filament\Staff\Resources\PerangkatDaerahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerangkatDaerahs extends ListRecords
{
    protected static string $resource = PerangkatDaerahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
