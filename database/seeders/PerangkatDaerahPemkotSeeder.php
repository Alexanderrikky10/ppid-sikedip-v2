<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\PerangkatDaerah;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PerangkatDaerahPemkotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Master untuk Kota/Kabupaten
        $kotas = [
            ['name' => 'Kota Pontianak'],
            ['name' => 'Kota Singkawang'],
            ['name' => 'Kabupaten Ketapang'],
            ['name' => 'Kabupaten Sintang'],
            ['name' => 'Kabupaten Kapuas Hulu'],
            ['name' => 'Kabupaten Bengkayang'],
            ['name' => 'Kabupaten Landak'],
            ['name' => 'Kabupaten Sekadau'],
            ['name' => 'Kabupaten Melawi'],
            ['name' => 'Kabupaten Kayong Utara'],
            ['name' => 'Kabupaten Kubu Raya'],
            ['name' => 'Kabupaten Mempawah'],
            ['name' => 'Kabupaten Sanggau'],
            ['name' => 'Kabupaten Sambas'],
        ];

        // Data Master untuk jenis Instansi
        $instansis = [
            ['name' => 'Sekretariat Daerah'],
            ['name' => 'Dinas Pendidikan'],
            ['name' => 'Dinas Kesehatan'],
            ['name' => 'Dinas Pekerjaan Umum'],
            ['name' => 'Dinas Sosial'],
            ['name' => 'Dinas Perhubungan'],
            ['name' => 'Dinas Pertanian'],
            ['name' => 'Dinas Perindustrian dan Perdagangan'],
            ['name' => 'Badan Kepegawaian Daerah'],
            ['name' => 'Badan Pengelolaan Keuangan Daerah'],
            ['name' => 'Badan Penanggulangan Bencana Daerah'],
            ['name' => 'Kecamatan'],
        ];

        // Mapping nama Kota/Kabupaten ke file logonya
        $logo_map = [
            'Kota Pontianak' => 'perangkat_daerah-images/Logo_Kota_Pontianak.png',
            'Kota Singkawang' => 'perangkat_daerah-images/Logo_Kota_Singkawang.png',
            'Kabupaten Ketapang' => 'perangkat_daerah-images/Logo_Kabupaten_Ketapang.jpeg',
            'Kabupaten Sintang' => 'perangkat_daerah-images/Logo_Kabupaten_Sintang.png',
            'Kabupaten Kapuas Hulu' => 'perangkat_daerah-images/Logo_Kapuas_Hulu.png',
            'Kabupaten Bengkayang' => 'perangkat_daerah-images/Logo_Kabupaten_Bengkayang.png',
            'Kabupaten Landak' => 'perangkat_daerah-images/Logo_Kabupaten_Landak.png',
            'Kabupaten Sekadau' => 'perangkat_daerah-images/Logo_Kabupaten_Sekadau.jpg',
            'Kabupaten Melawi' => 'perangkat_daerah-images/Logo_Kabupaten_Melawi.png',
            'Kabupaten Kayong Utara' => 'perangkat_daerah-images/Logo_Kayong_Utara.png',
            'Kabupaten Kubu Raya' => 'perangkat_daerah-images/Logo_kabupaten_Kubu_Raya.jpg',
            'Kabupaten Mempawah' => 'perangkat_daerah-images/Logo_Kabupaten_Mempawah.png',
            'Kabupaten Sanggau' => 'perangkat_daerah-images/Logo_Kabupaten_Sanggau.png',
            'Kabupaten Sambas' => 'perangkat_daerah-images/Logo_Kabupaten_Sambas.jpg',
        ];

        // Looping untuk setiap kota/kabupaten
        foreach ($kotas as $kota) {

            // LANGKAH 1: Buat atau temukan data Induk (Kota/Kabupaten).
            $parentKota = PerangkatDaerah::updateOrCreate(
                ['nama_perangkat_daerah' => $kota['name']], // Kunci pencarian
                [
                    'labele_perangkat_daerah' => $kota['name'],
                    'kategori_informasi_id' => 2,
                    'parent_id' => null, // Induk tidak punya parent
                    'slug' => Str::slug($kota['name']),
                    'images' => $logo_map[$kota['name']] ?? null,
                ]
            );

            // LANGKAH 2: Buat data anak (Instansi) untuk setiap Kota/Kabupaten.
            foreach ($instansis as $instansi) {
                $namaLengkapInstansi = $instansi['name'] . ' ' . $kota['name'];

                PerangkatDaerah::updateOrCreate(
                    ['nama_perangkat_daerah' => $namaLengkapInstansi], // Kunci pencarian untuk anak
                    [
                        'labele_perangkat_daerah' => $namaLengkapInstansi,
                        'kategori_informasi_id' => 2,
                        'parent_id' => $parentKota->id, // Mengambil ID dari objek induk
                        'slug' => Str::slug($namaLengkapInstansi),
                        'images' => null, // Instansi tidak memiliki logo sendiri
                    ]
                );
            }
        }
    }
}
