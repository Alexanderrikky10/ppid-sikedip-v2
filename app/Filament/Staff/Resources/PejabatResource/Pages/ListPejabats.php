<?php

namespace App\Filament\Staff\Resources\PejabatResource\Pages;

use App\Filament\Staff\Resources\PejabatResource;
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
