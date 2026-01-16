<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\PerangkatDaerah;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PerangkatDaerahPemprovSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // KHUSUS PEMPROV (Kategori ID = 1)
        // ==========================================

        // 1. Buat Parent Utama Pemprov
        $parentPemprov = PerangkatDaerah::updateOrCreate(
            ['nama_perangkat_daerah' => 'Pemerintah Provinsi Kalimantan Barat'], // Cek agar tidak duplikat
            [
                'labele_perangkat_daerah' => 'Pemprov Kalbar', // Sesuai typo di model (labele)
                'kategori_informasi_id' => 1, // ✅ ID 1 untuk Pemprov
                'parent_id' => null,
                'slug' => Str::slug('Pemerintah Provinsi Kalimantan Barat'),
                // Pastikan path image ini valid atau null dulu jika belum ada file
                'images' => 'perangkat_daerah-images/logo_pemprov_kalbar.png',
            ]
        );

        // 2. Daftar OPD / Dinas di Tingkat Provinsi
        $opdProvinsi = [
            'Sekretariat Daerah',
            'Sekretariat DPRD',
            'Inspektorat Daerah',
            'Dinas Komunikasi dan Informatika',
            'Dinas Pendidikan dan Kebudayaan',
            'Dinas Kesehatan',
            'Dinas Pekerjaan Umum dan Penataan Ruang',
            'Dinas Perumahan Rakyat dan Kawasan Permukiman',
            'Dinas Sosial',
            'Dinas Pemberdayaan Masyarakat dan Desa',
            'Badan Perencanaan Pembangunan Daerah',
            'Badan Keuangan dan Aset Daerah',
            'Badan Pendapatan Daerah',
            'Badan Kepegawaian Daerah',
            'Satuan Polisi Pamong Praja',
            // Tambahkan dinas lain jika perlu
        ];

        // 3. Loop untuk membuat anak-anaknya (OPD)
        foreach ($opdProvinsi as $opd) {
            $namaLengkap = $opd . ' Provinsi Kalimantan Barat';

            PerangkatDaerah::updateOrCreate(
                ['nama_perangkat_daerah' => $namaLengkap],
                [
                    'labele_perangkat_daerah' => $opd,
                    'kategori_informasi_id' => 1, // ✅ ID 1 (Pemprov)
                    'parent_id' => $parentPemprov->id, // Menginduk ke Parent di atas
                    'slug' => Str::slug($namaLengkap),
                    'images' => null, // Default null
                ]
            );
        }
    }
}
