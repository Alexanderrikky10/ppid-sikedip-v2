<?php

namespace App\Filament\Staff\Resources\PejabatResource\Pages;

use App\Filament\Staff\Resources\PejabatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPejabat extends EditRecord
{
    protected static string $resource = PejabatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
