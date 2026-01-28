<?php

use App\Http\Controllers\LayananInformasi\CetakInformasiUserController;
use App\Http\Controllers\LayananInformasi\TataCaraController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Beranda\BerandaUserController;
use App\Http\Controllers\Cetak\CetakInformasiController;
use App\Http\Controllers\WelcomePage\WelcomePageController;
use App\Http\Controllers\LayananInformasi\KeberatanInformasiController;
use App\Http\Controllers\LayananInformasi\PermohonanInformasiController;


// wecome page route
Route::get('/', [WelcomePageController::class, 'welcome'])
    ->name('welcome.page');

// beranda user route
Route::get('/beranda', [BerandaUserController::class, 'index'])
    ->name('beranda.index');

// layanan informasi route
Route::get('/permohonan-informasi', [PermohonanInformasiController::class, 'index'])->name('permohonan-informasi');
Route::post("/permohonan-informasi", [PermohonanInformasiController::class, 'store'])->name('permohonan-informasi.store');

// keberatan informasi route
Route::get('/keberatan-informasi', [KeberatanInformasiController::class, 'index'])
    ->name('keberatan-informasi');
Route::post('/keberatan-informasi', [KeberatanInformasiController::class, 'store'])
    ->name('keberatan-informasi.store');

// route tata cara layanan informasi
Route::get('/tata-cara-layanan-informasi', [TataCaraController::class, 'index'])->name('tata-cara-layanan-informasi');

// route cetak informasi
Route::get('/cetak-informasi', [CetakInformasiUserController::class, 'index'])->name('cetak-informasi');
// Route untuk mengambil data permohonan via AJAX berdasarkan NIK
Route::get('/get-permohonan-by-nik', [KeberatanInformasiController::class, 'getPermohonanByNik'])->name('ajax.get-permohonan');



// cetak informasi routes
Route::get('/cetak-informasi/laporan', [CetakInformasiController::class, 'print'])
    ->name('cetak.informasi.laporan');

Route::get('/cetak-informasi/pdf', [CetakInformasiController::class, 'downloadPdf'])
    ->name('cetak.informasi.pdf');

Route::get('/cetak-informasi/excel', [CetakInformasiController::class, 'downloadExcel'])
    ->name('cetak.informasi.excel');


