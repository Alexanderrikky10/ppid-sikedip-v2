<?php

namespace App\Filament\Resources\WelcomePageResource\Pages;

use App\Filament\Resources\WelcomePageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWelcomePage extends CreateRecord
{
    protected static string $resource = WelcomePageResource::class;
}
