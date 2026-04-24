# To-Do List SIKEDIP

Daftar perbaikan dan pengembangan fitur untuk modul Informasi Publik.

## 🚀 Filament Pages (Modul Informasi)

Target: Mengubah tampilan dari Form standar menjadi Tabel dengan fitur _Toggle Form_ dan _Action Group_ di sebelah kiri.

### 1. Halaman Informasi Pemprov (`InformasiPemprov.php`)

- [✅] Implementasi `HasTable` dan `InteractsWithTable`.
- [✅] Menambahkan kolom Tabel dengan Badges (Warna: Info, Gray, Warning).
- [✅] Memindahkan Action Group (Edit/Hapus) ke posisi kiri (`ActionsPosition::BeforeColumns`).
- [✅] Implementasi logika _Toggle_ (Sembunyikan/Tampilkan) Form vs Tabel.
- [✅] Memastikan Query Filter `kategori_informasi_id = 1`.

- [✅] buat fungsi cetak di layanan informasi

### 2. halaman instansi pemerintah (`halaman user`)

- [✅] buat modal untuk pemprov utama lalu
- [✅] buat tampilan untuk sub sertakan logo
- [✅] buat tampilan untuk pemkab/kota
- [✅] buat tampilan untuk bumd
- [✅] tambahkan logo di masing masing halaman detail antara bumd dan juga pemprov

### 3. halaman laporan dan analisis (`halaman user`)

- [✅] panduan penyusunan dip
- [✅] tampilan grafik pemprov
- [✅] tampilan grafik pemkab/kota
- [✅] tampilan grafik bumd

### 4. halaman inforamsi untuk ketiganya (`halaman admin  `)

- [✅] tampilan awal di sesuikan lagi
- [✅] tambahka untuk lihat detail
- [✅] menu edit cepat

### 5. urutan menu (`halaman admin  `)

- [✅] sesuaikan tmapilan halaman admin untuk urutan dari menu
- [] perbaiki chart di halaman admin dan tambahkan (tambahkan untuk stat jumlah informasi berdasarkan kategori masing masing tidak lupa untuk tambahkan total dowload )
- [] sesuaikan lagi tampilan atau cek lagi satu persatu menu di halaman admin

### 6. penyusunan logika untuk visitor (`logika di bagian penguna user   `)

- [✅] perbaiki logika user untuk menghitung jumlah visitor web
- [✅] perbaiki tampilan untuk welcome itu di ganti dengan mengunakan slider

### 7. membuat halaman penguna staff (`membuat menu untuk staff  `)

- [✅] buat menu untuk tampilan dari staff panel
- [✅] perbaiki modal di halaman user
- [] tambahkan notif ketika ada permohonan masuk

-- surat riset di tambahkan
-- mita saran ke bulisna terkait ini implemenasi atau bagai mana

tanggal 10 konsul judul sama pembimbing 1
dan pembimbing 2
