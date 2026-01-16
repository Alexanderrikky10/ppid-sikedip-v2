<?php

namespace App\Filament\Resources\PejabatResource\Pages;

use App\Filament\Resources\PejabatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPejabats extends ListRecords
{
    protected static string $resource = PejabatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
