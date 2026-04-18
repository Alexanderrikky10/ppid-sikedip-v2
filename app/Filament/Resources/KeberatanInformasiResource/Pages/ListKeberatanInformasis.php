<?php

namespace App\Filament\Resources\KeberatanInformasiResource\Pages;

use App\Filament\Resources\KeberatanInformasiResource;
use App\Filament\Resources\KeberatanInformasiResource\Widgets\KeberatanInformasiStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeberatanInformasis extends ListRecords
{
    protected static string $resource = KeberatanInformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            // Tambahkan widget statistik di sini jika diperlukan
            KeberatanInformasiStats::class,
        ];
    }
}
