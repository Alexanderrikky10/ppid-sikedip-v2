<?php

namespace App\Filament\Resources\BerandaContentResource\Pages;

use App\Filament\Resources\BerandaContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBerandaContent extends EditRecord
{
    protected static string $resource = BerandaContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
