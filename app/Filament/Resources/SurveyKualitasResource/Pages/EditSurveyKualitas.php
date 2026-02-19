<?php

namespace App\Filament\Resources\SurveyKualitasResource\Pages;

use App\Filament\Resources\SurveyKualitasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyKualitas extends EditRecord
{
    protected static string $resource = SurveyKualitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
