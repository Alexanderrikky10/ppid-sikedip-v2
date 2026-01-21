# To-Do List SIKEDIP

Daftar perbaikan dan pengembangan fitur untuk modul Informasi Publik.


## 🚀 Filament Pages (Modul Informasi)
Target: Mengubah tampilan dari Form standar menjadi Tabel dengan fitur *Toggle Form* dan *Action Group* di sebelah kiri.

### 1. Halaman Informasi Pemprov (`InformasiPemprov.php`)
- [x] Implementasi `HasTable` dan `InteractsWithTable`.
- [x] Menambahkan kolom Tabel dengan Badges (Warna: Info, Gray, Warning).
- [x] Memindahkan Action Group (Edit/Hapus) ke posisi kiri (`ActionsPosition::BeforeColumns`).
- [x] Implementasi logika *Toggle* (Sembunyikan/Tampilkan) Form vs Tabel.
- [x] Memastikan Query Filter `kategori_informasi_id = 1`.

### 2. Halaman Informasi Pemkab/Kota (`InformasiPemkabKota.php`)
- [ ] Implementasi `HasTable` dan `InteractsWithTable`.
- [ ] Menambahkan kolom Tabel dengan Badges.
- [ ] Memindahkan Action Group ke posisi kiri.
- [ ] Implementasi logika *Toggle* Form vs Tabel.
- [ ] Memastikan Query Filter `kategori_informasi_id = 2`.
- [ ] Update view blade (`informasi-pemkab-kota.blade.php`).

### 3. Halaman Informasi BUMD (`InformasiBumd.php`)
- [ ] Implementasi `HasTable` dan `InteractsWithTable`.
- [ ] Menambahkan kolom Tabel dengan Badges.
- [ ] Memindahkan Action Group ke posisi kiri.
- [ ] Implementasi logika *Toggle* Form vs Tabel.
- [ ] Memastikan Query Filter `kategori_informasi_id = 3`.
- [ ] Update view blade (`informasi-bumd.blade.php`).

## 🧹 General Clean-up
- [ ] Jalankan `php artisan migrate:fresh --seed` untuk memastikan database bersih dan fresh.
- [ ] Cek kembali import class (Namespace) di setiap file Page agar tidak ada error `Class not found`.
- [ ] Test fitur Upload Media (Pastikan driver `public` atau `minio` sudah sesuai di `.env`).

---
*Last Updated: 2026*

## halaman cetak 
- [ ] membuat tampilan cetak dengan mengunakan custome filament
- [ ] membuat fungsi dari cetak dan mengunakan konfigurasi dari dom pdf 
- [ ] sesuaikan lagi pengangung jawab untuk di bagian cetak 
- [ ] 
- [ ] 