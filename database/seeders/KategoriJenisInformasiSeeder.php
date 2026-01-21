<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriJenisInformasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data diambil murni dari Poin A sampai K aturan yang dilampirkan
        $dataKategori = [
            [
                'nama_kategori' => 'Informasi Tentang Profil Badan Publik',
                'slug' => Str::slug('Informasi Tentang Profil Badan Publik'),
            ],
            [
                'nama_kategori' => 'Ringkasan Informasi Tentang Program dan Kegiatan',
                'slug' => Str::slug('Ringkasan Informasi Tentang Program dan Kegiatan'),
            ],
            [
                'nama_kategori' => 'Ringkasan Informasi Tentang Kinerja',
                'slug' => Str::slug('Ringkasan Informasi Tentang Kinerja'),
            ],
            [
                'nama_kategori' => 'Ringkasan Laporan Keuangan Yang Telah Diaudit',
                'slug' => Str::slug('Ringkasan Laporan Keuangan Yang Telah Diaudit'),
            ],
            [
                'nama_kategori' => 'Ringkasan Laporan Akses Informasi Publik',
                'slug' => Str::slug('Ringkasan Laporan Akses Informasi Publik'),
            ],
            [
                'nama_kategori' => 'Informasi Tentang Peraturan, Keputusan, dan Kebijakan',
                'slug' => Str::slug('Informasi Tentang Peraturan Keputusan dan Kebijakan'),
            ],
            [
                'nama_kategori' => 'Informasi Tentang Prosedur Memperoleh Informasi Publik',
                'slug' => Str::slug('Informasi Tentang Prosedur Memperoleh Informasi Publik'),
            ],
            [
                'nama_kategori' => 'Informasi Tentang Tata Cara Pengaduan Penyalahgunaan Wewenang',
                'slug' => Str::slug('Informasi Tentang Tata Cara Pengaduan Penyalahgunaan Wewenang'),
            ],
            [
                'nama_kategori' => 'Informasi Tentang Pengadaan Barang dan Jasa',
                'slug' => Str::slug('Informasi Tentang Pengadaan Barang dan Jasa'),
            ],
            [
                'nama_kategori' => 'Informasi Tentang Ketenagakerjaan',
                'slug' => Str::slug('Informasi Tentang Ketenagakerjaan'),
            ],
            [
                'nama_kategori' => 'Informasi Tentang Prosedur Peringatan Dini dan Evakuasi Darurat',
                'slug' => Str::slug('Informasi Tentang Prosedur Peringatan Dini dan Evakuasi Darurat'),
            ],
        ];

        // Melakukan insert data
        foreach ($dataKategori as $kategori) {
            DB::table('kategori_jenis_informasis')->updateOrInsert(
                ['slug' => $kategori['slug']], // Cek agar tidak duplikat
                $kategori
            );
        }
    }
}
