<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriInformasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('kategori_informasis')->insert([
            [
                'nama_kategori' => 'Informasi Pemprov',
                'slug' => 'informasi-pemprov',
            ],
            [
                'nama_kategori' => 'Informasi Pemkab/Kota',
                'slug' => 'informasi-pemkab-kota',
            ],
            [
                'nama_kategori' => 'Informasi BUMD',
                'slug' => 'informasi-bumd',
            ],
        ]);
    }
}
