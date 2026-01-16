<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\PerangkatDaerah;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PerangkatDaerahBumdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Grouping Parent untuk BUMD (Opsional, agar rapi di Tree View)
        $parentBumd = PerangkatDaerah::updateOrCreate(
            ['nama_perangkat_daerah' => 'BUMD Kalimantan Barat'],
            [
                'labele_perangkat_daerah' => 'BUMD Kalbar',
                'kategori_informasi_id' => 3,
                'parent_id' => null,
                'slug' => Str::slug('BUMD Kalimantan Barat'),
                'images' => 'perangkat_daerah-images/logo_bumd_default.png',
            ]
        );

        // 2. Daftar Perusahaan Daerah
        $bumdList = [
            ['name' => 'PT Bank Pembangunan Daerah (Bank Kalbar)', 'short' => 'Bank Kalbar'],
            ['name' => 'Perusda Aneka Usaha', 'short' => 'Perusda Aneka Usaha'],
            ['name' => 'PT Jamkrida Kalbar', 'short' => 'Jamkrida Kalbar'],
            ['name' => 'PT Kalbar Sejahtera', 'short' => 'Kalbar Sejahtera'],
        ];

        // 3. Loop Data
        foreach ($bumdList as $bumd) {
            PerangkatDaerah::updateOrCreate(
                ['nama_perangkat_daerah' => $bumd['name']],
                [
                    'labele_perangkat_daerah' => $bumd['short'],
                    'kategori_informasi_id' => 3,
                    'parent_id' => $parentBumd->id,
                    'slug' => Str::slug($bumd['name']),
                    'images' => null, // Bisa diisi path spesifik jika ada logo bank dll
                ]
            );
        }
    }
}
