<?php

namespace App\Filament\Resources\PermohonanInformasiResource\Pages;

use App\Filament\Resources\PermohonanInformasiResource;
use App\Models\PermohonanInformasi;
use Filament\Resources\Pages\CreateRecord;

class CreatePermohonanInformasi extends CreateRecord
{
    protected static string $resource = PermohonanInformasiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['no_registrasi'] = $this->generateNoRegistrasi();

        return $data;
    }

    private function generateNoRegistrasi(): string
    {
        $prefix = 'REG';
        $date = now()->format('Ymd');

        // Cari nomor urut terakhir hari ini agar tidak tabrakan (lebih aman dari rand)
        $lastToday = PermohonanInformasi::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->value('no_registrasi');

        if ($lastToday) {
            // Ambil 4 digit terakhir lalu increment
            $lastSeq = (int) substr($lastToday, -4);
            $seq = str_pad($lastSeq + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $seq = '0001';
        }

        return "{$prefix}-{$date}-{$seq}";
    }
}