<?php

namespace App\Filament\Staff\Resources\PerangkatDaerahResource\Pages;

use App\Filament\Staff\Resources\PerangkatDaerahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerangkatDaerah extends EditRecord
{
    protected static string $resource = PerangkatDaerahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
