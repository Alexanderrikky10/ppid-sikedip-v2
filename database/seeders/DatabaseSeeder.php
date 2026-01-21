<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\JabatanSeeder;
use Database\Seeders\PejabatSeeder;
use Database\Seeders\KategoriInformasiSeeder;
use Database\Seeders\PerangkatDaerahBumdSeeder;
use Database\Seeders\KlasifikasiInformasiSeeder;
use Database\Seeders\PerangkatDaerahPemkotSeeder;
use Database\Seeders\KategoriJenisInformasiSeeder;
use Database\Seeders\PerangkatDaerahPemprovSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KlasifikasiInformasiSeeder::class,
            KategoriJenisInformasiSeeder::class,
            KategoriInformasiSeeder::class,
            PerangkatDaerahBumdSeeder::class,
            PerangkatDaerahPemprovSeeder::class,
            PerangkatDaerahPemkotSeeder::class,
            JabatanSeeder::class,
            PejabatSeeder::class,
        ]);
        // User::factory(10)->create();


    }
}
