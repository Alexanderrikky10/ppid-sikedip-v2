<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KlasifikasiInformasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('klasifikasi_informasis')->insert([
            [
                'nama_klasifikasi' => 'wajib disediakan dan diumumkan secara berkala',
                'slug' => 'wajib-disediakan-dan-diumumkan-secara-berkala',
            ],
            [
                'nama_klasifikasi' => 'wajib diumumkan secara serta merta',
                'slug' => 'wajib-diumumkan-secara-serta-merta',
            ],
            [
                'nama_klasifikasi' => 'wajib tersedia setiap saat',
                'slug' => 'wajib-tersedia-setiap-saat',
            ],
        ]);
    }
}
