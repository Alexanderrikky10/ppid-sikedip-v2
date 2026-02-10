<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InformasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('informasis')->insert([
            [
                'tahun' => 2024,
                'perangkat_daerah_id' => 1,
                'klasifikasi_informasi_id' => 1,
                'kategori_jenis_informasi_id' => 1,
                'kategori_informasi_id' => 1,
                'judul_informasi' => 'Profil Komisi Informasi Provinsi Kalimantan Barat',
                'ringkasan' => 'Informasi lengkap tentang struktur organisasi, tugas, dan fungsi Komisi Informasi Provinsi.',
                'penjelasan' => 'Dokumen ini memuat visi, misi, tugas pokok dan fungsi, struktur organisasi, serta profil komisioner.',
                'pejabat_pj' => 'Ketua Komisi Informasi Provinsi Kalbar',
                'waktu_tempat' => 'Kantor Komisi Informasi Provinsi, Setiap hari kerja pukul 08.00-16.00 WIB',
                'pj_penerbit_informasi' => 'Sekretariat Komisi Informasi',
                'format_informasi' => 'Soft Copy',
                'waktu_penyimpanan' => 'Diperbarui setiap periode jabatan',
                'media' => 'uploads/dokumen/profil-ki-kalbar-2024.pdf', // Path File Diperbaiki
                'slug' => Str::slug('Profil Komisi Informasi Provinsi Kalimantan Barat'),
                'tanggal_publikasi' => '2024-01-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => 2024,
                'perangkat_daerah_id' => 1,
                'klasifikasi_informasi_id' => 1,
                'kategori_jenis_informasi_id' => 5,
                'kategori_informasi_id' => 1,
                'judul_informasi' => 'Laporan Layanan Informasi Publik Tahun 2024',
                'ringkasan' => 'Ringkasan statistik permohonan informasi publik yang diterima dan diproses oleh Komisi Informasi.',
                'penjelasan' => 'Laporan ini berisi data jumlah permohonan informasi, permohonan yang dikabulkan, ditolak, dll.',
                'pejabat_pj' => 'Sekretaris Komisi Informasi Provinsi',
                'waktu_tempat' => 'Kantor Komisi Informasi, tersedia setiap hari kerja',
                'pj_penerbit_informasi' => 'Bidang Penyelesaian Sengketa Informasi',
                'format_informasi' => 'Hard Copy & Soft Copy',
                'waktu_penyimpanan' => 'Disimpan selama 5 tahun',
                'media' => 'uploads/dokumen/laporan-layanan-ki-2024.pdf', // Path File Diperbaiki
                'slug' => Str::slug('Laporan Layanan Informasi Publik Tahun 2024'),
                'tanggal_publikasi' => '2024-03-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => 2024,
                'perangkat_daerah_id' => 1,
                'klasifikasi_informasi_id' => 2,
                'kategori_jenis_informasi_id' => 8,
                'kategori_informasi_id' => 1,
                'judul_informasi' => 'Prosedur Pengaduan Pelanggaran Konten Siaran',
                'ringkasan' => 'Tata cara dan mekanisme pengaduan masyarakat terkait pelanggaran standar program siaran.',
                'penjelasan' => 'Panduan lengkap bagi masyarakat untuk menyampaikan pengaduan terhadap lembaga penyiaran.',
                'pejabat_pj' => 'Ketua KPID Provinsi Kalbar',
                'waktu_tempat' => 'Kantor KPID, Online 24/7 melalui website resmi',
                'pj_penerbit_informasi' => 'Divisi Pengaduan Masyarakat',
                'format_informasi' => 'Hard Copy & Soft Copy',
                'waktu_penyimpanan' => 'Selama masih berlaku dan diperbarui berkala',
                'media' => 'uploads/dokumen/sop-pengaduan-kpid.pdf', // Path File Diperbaiki
                'slug' => Str::slug('Prosedur Pengaduan Pelanggaran Konten Siaran'),
                'tanggal_publikasi' => '2024-02-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => 2024,
                'perangkat_daerah_id' => 1,
                'klasifikasi_informasi_id' => 1,
                'kategori_jenis_informasi_id' => 3,
                'kategori_informasi_id' => 1,
                'judul_informasi' => 'Laporan Kinerja KPID Provinsi Kalbar 2024',
                'ringkasan' => 'Laporan kinerja tahunan KPID dalam mengawasi dan mengatur lembaga penyiaran.',
                'penjelasan' => 'Dokumen berisi capaian kinerja pengawasan isi siaran, evaluasi terhadap lembaga penyiaran.',
                'pejabat_pj' => 'Sekretaris KPID Provinsi',
                'waktu_tempat' => 'Kantor KPID, setiap hari kerja',
                'pj_penerbit_informasi' => 'Sekretariat KPID',
                'format_informasi' => 'Soft Copy',
                'waktu_penyimpanan' => 'Disimpan selama 5 tahun',
                'media' => 'uploads/dokumen/lakip-kpid-2024.pdf', // Path File Diperbaiki
                'slug' => Str::slug('Laporan Kinerja KPID Provinsi Kalbar 2024'),
                'tanggal_publikasi' => '2024-05-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => 2023,
                'perangkat_daerah_id' => 1,
                'klasifikasi_informasi_id' => 1,
                'kategori_jenis_informasi_id' => 4,
                'kategori_informasi_id' => 1,
                'judul_informasi' => 'Laporan Keuangan BAZNAS Provinsi Kalbar 2023',
                'ringkasan' => 'Laporan keuangan yang telah diaudit mengenai penerimaan dan penyaluran zakat tahun 2023.',
                'penjelasan' => 'Laporan audit keuangan BAZNAS mencakup total penerimaan zakat, infaq, sedekah.',
                'pejabat_pj' => 'Ketua BAZNAS Provinsi Kalbar',
                'waktu_tempat' => 'Kantor BAZNAS Provinsi, Setiap hari kerja pukul 08.00-16.00 WIB',
                'pj_penerbit_informasi' => 'Divisi Keuangan dan Akuntansi',
                'format_informasi' => 'Soft Copy',
                'waktu_penyimpanan' => 'Disimpan selama 10 tahun',
                'media' => 'uploads/dokumen/lapkeu-baznas-2023.pdf', // Path File Diperbaiki
                'slug' => Str::slug('Laporan Keuangan BAZNAS Provinsi Kalbar 2023'),
                'tanggal_publikasi' => '2024-04-25',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => 2024,
                'perangkat_daerah_id' => 2, // Asumsi ID 2 adalah Perangkat Daerah Kab/Kota
                'klasifikasi_informasi_id' => 1, // Publik
                'kategori_jenis_informasi_id' => 4, // Keuangan
                'kategori_informasi_id' => 2, // KABUPATEN/KOTA
                'judul_informasi' => 'Ringkasan APBD Kota Pontianak Tahun Anggaran 2024',
                'ringkasan' => 'Informasi ringkasan Anggaran Pendapatan dan Belanja Daerah (APBD) Kota Pontianak.',
                'penjelasan' => 'Dokumen memuat rincian pendapatan daerah, belanja daerah, dan pembiayaan daerah yang telah disahkan.',
                'pejabat_pj' => 'Sekretaris Daerah Kota Pontianak',
                'waktu_tempat' => 'Kantor Walikota Pontianak, Senin-Jumat', // Perbaikan typo 'waktu_tempat' ke 'waktu_tempat'
                'pj_penerbit_informasi' => 'BKAD Kota Pontianak',
                'format_informasi' => 'Soft Copy',
                'waktu_penyimpanan' => 'Selama 10 Tahun',
                'media' => 'uploads/dokumen/ringkasan-apbd-pontianak-2024.pdf',
                'slug' => Str::slug('Ringkasan APBD Kota Pontianak Tahun Anggaran 2024'),
                'tanggal_publikasi' => '2024-01-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => 2023,
                'perangkat_daerah_id' => 2,
                'klasifikasi_informasi_id' => 1, // Publik
                'kategori_jenis_informasi_id' => 2, // Peraturan/Kebijakan
                'kategori_informasi_id' => 2, // KABUPATEN/KOTA
                'judul_informasi' => 'Peraturan Bupati Kubu Raya No. 15 Tahun 2023 tentang Tata Ruang',
                'ringkasan' => 'Peraturan mengenai Rencana Detail Tata Ruang (RDTR) Wilayah Perkotaan Sungai Raya.',
                'penjelasan' => 'Berisi peta zonasi, aturan pemanfaatan ruang, dan ketentuan perizinan bangunan.',
                'pejabat_pj' => 'Kepala Dinas PUPR Kabupaten Kubu Raya',
                'waktu_tempat' => 'Dinas PUPR Kubu Raya, Jam Kerja',
                'pj_penerbit_informasi' => 'Bidang Tata Ruang',
                'format_informasi' => 'Hard Copy & Soft Copy',
                'waktu_penyimpanan' => 'Selama berlaku',
                'media' => 'uploads/dokumen/perbup-rdtr-kuburaya.pdf',
                'slug' => Str::slug('Peraturan Bupati Kubu Raya No 15 Tahun 2023 tentang Tata Ruang'),
                'tanggal_publikasi' => '2023-06-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => 2024,
                'perangkat_daerah_id' => 2,
                'klasifikasi_informasi_id' => 1,
                'kategori_jenis_informasi_id' => 5, // Layanan
                'kategori_informasi_id' => 2, // KABUPATEN/KOTA
                'judul_informasi' => 'Standar Pelayanan Pembuatan KTP-el Disdukcapil Singkawang',
                'ringkasan' => 'Informasi persyaratan, alur, dan jangka waktu pembuatan KTP Elektronik.',
                'penjelasan' => 'Panduan lengkap bagi warga Kota Singkawang untuk pengurusan administrasi kependudukan.',
                'pejabat_pj' => 'Kepala Disdukcapil Kota Singkawang',
                'waktu_tempat' => 'Mall Pelayanan Publik Singkawang',
                'pj_penerbit_informasi' => 'Bidang Pendaftaran Penduduk',
                'format_informasi' => 'Soft Copy',
                'waktu_penyimpanan' => 'Diperbarui jika ada perubahan regulasi',
                'media' => 'uploads/dokumen/sop-ktp-singkawang.pdf',
                'slug' => Str::slug('Standar Pelayanan Pembuatan KTP-el Disdukcapil Singkawang'),
                'tanggal_publikasi' => '2024-02-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===================================================================
            // 3. DATA INFORMASI BUMD (ID: 3)
            // ===================================================================
            [
                'tahun' => 2023,
                'perangkat_daerah_id' => 3, // Asumsi ID 3 adalah Entitas BUMD
                'klasifikasi_informasi_id' => 1, // Publik
                'kategori_jenis_informasi_id' => 3, // Laporan Kinerja
                'kategori_informasi_id' => 3, // BUMD
                'judul_informasi' => 'Laporan Tahunan (Annual Report) Bank Kalbar Tahun 2023',
                'ringkasan' => 'Laporan pertanggungjawaban Direksi dan Komisaris mengenai kinerja keuangan Bank Kalbar.',
                'penjelasan' => 'Memuat laporan posisi keuangan, laba rugi, tata kelola perusahaan, dan tanggung jawab sosial (CSR).',
                'pejabat_pj' => 'Direktur Utama Bank Kalbar',
                'waktu_tempat' => 'Kantor Pusat Bank Kalbar, Tersedia di Website',
                'pj_penerbit_informasi' => 'Divisi Corporate Secretary',
                'format_informasi' => 'Soft Copy',
                'waktu_penyimpanan' => 'Disimpan Permanen',
                'media' => 'uploads/dokumen/annual-report-bank-kalbar-2023.pdf',
                'slug' => Str::slug('Laporan Tahunan Annual Report Bank Kalbar Tahun 2023'),
                'tanggal_publikasi' => '2024-04-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => 2024,
                'perangkat_daerah_id' => 3,
                'klasifikasi_informasi_id' => 1,
                'kategori_jenis_informasi_id' => 2, // Regulasi/Tarif
                'kategori_informasi_id' => 3, // BUMD
                'judul_informasi' => 'Struktur Tarif Air Minum Perumdam Tirta Khatulistiwa 2024',
                'ringkasan' => 'Daftar tarif dasar air minum berdasarkan klasifikasi pelanggan rumah tangga dan industri.',
                'penjelasan' => 'SK Direksi mengenai penyesuaian tarif air bersih yang berlaku di wilayah Kota Pontianak.',
                'pejabat_pj' => 'Direktur Utama Perumdam Tirta Khatulistiwa',
                'waktu_tempat' => 'Kantor Pusat Perumdam, Loket Pembayaran',
                'pj_penerbit_informasi' => 'Bagian Hubungan Langganan',
                'format_informasi' => 'Hard Copy',
                'waktu_penyimpanan' => 'Selama tarif berlaku',
                'media' => 'uploads/dokumen/tarif-pdam-2024.jpg',
                'slug' => Str::slug('Struktur Tarif Air Minum Perumdam Tirta Khatulistiwa 2024'),
                'tanggal_publikasi' => '2024-01-05',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun' => 2023,
                'perangkat_daerah_id' => 3,
                'klasifikasi_informasi_id' => 1,
                'kategori_jenis_informasi_id' => 6, // Pengadaan Barang Jasa
                'kategori_informasi_id' => 3, // BUMD
                'judul_informasi' => 'Pengumuman Lelang Pengadaan IT Jamkrida Kalbar',
                'ringkasan' => 'Informasi lelang pengadaan perangkat server dan jaringan untuk PT. Jamkrida Kalbar.',
                'penjelasan' => 'Dokumen berisi syarat kualifikasi peserta, jadwal lelang, dan spesifikasi teknis perangkat.',
                'pejabat_pj' => 'Direktur PT. Jamkrida Kalbar',
                'waktu_tempat' => 'Kantor Jamkrida Kalbar',
                'pj_penerbit_informasi' => 'Panitia Pengadaan Barang dan Jasa',
                'format_informasi' => 'Soft Copy',
                'waktu_penyimpanan' => '5 Tahun setelah proyek selesai',
                'media' => 'uploads/dokumen/lelang-it-jamkrida.pdf',
                'slug' => Str::slug('Pengumuman Lelang Pengadaan IT Jamkrida Kalbar'),
                'tanggal_publikasi' => '2023-11-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


    }
}
