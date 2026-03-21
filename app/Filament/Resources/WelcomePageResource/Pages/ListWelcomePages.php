<?php

namespace App\Filament\Resources\WelcomePageResource\Pages;

use App\Filament\Resources\WelcomePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWelcomePages extends ListRecords
{
    protected static string $resource = WelcomePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
