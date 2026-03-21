<?php

namespace App\Filament\Resources\WelcomePageResource\Pages;

use App\Filament\Resources\WelcomePageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWelcomePage extends EditRecord
{
    protected static string $resource = WelcomePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
