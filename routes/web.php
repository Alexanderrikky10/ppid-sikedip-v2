<?php

use App\Http\Controllers\Admin\BerandaAdminController;
use App\Http\Controllers\Admin\InformasiPemprovController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SSOController;
use App\Http\Controllers\Beranda\BerandaUserController;
use App\Http\Controllers\Cetak\CetakInformasiController;
use App\Http\Controllers\DaftarInformasi\DaftarInformasiBumdController;
use App\Http\Controllers\DaftarInformasi\DaftarInformasiController;
use App\Http\Controllers\DaftarInformasi\DaftarInformasiDetailController;
use App\Http\Controllers\DaftarInformasi\DaftarInformasiPemkabController;
use App\Http\Controllers\DaftarInformasi\DaftarInformasiPemprovController;
use App\Http\Controllers\LaporanAnalisis\GrafikBumd;
use App\Http\Controllers\LaporanAnalisis\GrafikPemkabKota;
use App\Http\Controllers\LaporanAnalisis\GrafikPemprov;
use App\Http\Controllers\LaporanAnalisis\PanduanDipController;
use App\Http\Controllers\LayananInformasi\CetakInformasiUserController;
use App\Http\Controllers\LayananInformasi\KeberatanInformasiController;
use App\Http\Controllers\LayananInformasi\PermohonanInformasiController;
use App\Http\Controllers\LayananInformasi\SurveyKualitasController;
use App\Http\Controllers\LayananInformasi\TataCaraController;
use App\Http\Controllers\WelcomePage\WelcomePageController;
use Illuminate\Support\Facades\Route;


// wecome page route
Route::get('/', [WelcomePageController::class, 'welcome'])
    ->name('welcome.page');

//login route
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//sso routes
Route::controller(SSOController::class)->prefix('sso')->name('sso.')->group(function () {
    Route::get('/redirect', 'redirect')->name('redirect');
    Route::get('/callbacksso', 'callback')->name('callback'); // URL di route tetap 'callbacksso'
    Route::post('/auth/sso/logout', 'logout')->name('logout'); // URL di route tetap '/auth/sso/logout'
});

// beranda user route
Route::get('/beranda', [BerandaUserController::class, 'index'])
    ->name('beranda.index');

// daftar informasi route
Route::get('/daftar-informasi-publik', [DaftarInformasiController::class, 'index'])
    ->name('daftar-informasi-publik');

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

// route survey kualitas layanan informasi
Route::get('/survey-kualitas-layanan-informasi', [SurveyKualitasController::class, 'surveyKualitas'])->name('survey-kualitas-informasi');
Route::post('/survey-kualitas-layanan-informasi', [SurveyKualitasController::class, 'store'])->name('survey-kualitas-informasi.store');

// route cetak informasi
Route::get('/cetak-informasi', [CetakInformasiUserController::class, 'index'])->name('cetak-informasi');
// Route untuk mengambil data permohonan via AJAX berdasarkan NIK
Route::get('/get-permohonan-by-nik', [KeberatanInformasiController::class, 'getPermohonanByNik'])->name('ajax.get-permohonan');


// daftar informasi pemprov route
Route::get('/daftar-informasi/pemprov/{tahun}', [DaftarInformasiPemprovController::class, 'index'])
    ->name('daftar-informasi.pemprov');
Route::get('/daftar-informasi/pemprov', [DaftarInformasiPemprovController::class, 'instansiPemprov'])
    ->name('daftar-informasi.instansi');
Route::get('daftar-informasi/{slug}', [DaftarInformasiPemprovController::class, 'pemprovList'])->name('daftar-informasi-pemprov.list');

// daftar informasi pemkab route
Route::get('/daftar-informasi-pemkab', [DaftarInformasiPemkabController::class, 'index'])->name('daftar-informasi.pemkab');
Route::get('/daftar-informasi/pemkab/{slug}', [DaftarInformasiPemkabController::class, 'pemkabList'])->name('daftar-informasi-pemkab.list');

//daftar informasi bumd route
Route::get('/daftar-informasi-bumd', [DaftarInformasiBumdController::class, 'index'])->name('daftar-informasi.bumd');
Route::get('/daftar-informasi/bumd/{slug}', [DaftarInformasiBumdController::class, 'bumdList'])->name('daftar-informasi-bumd.list');

// laporan dan analisis route
Route::get('/grafik-bumd', [GrafikBumd::class, 'bumdGrafik'])->name('grafik-bumd');
Route::get('/grafik-pemkab', [GrafikPemkabKota::class, 'grafikPemkabKota'])->name('grafik-pemkabkota');
Route::get('/grafik-pemprov', [GrafikPemprov::class, 'grafikPemprov'])->name('grafik-pemprov');
Route::get('/panduan-penyusunan-dip', [PanduanDipController::class, 'panduanDip'])->name('panduan-penyusunan-dip');

//detail informasi 
Route::get('/detail-informasi/{id}', [DaftarInformasiDetailController::class, 'show'])->name('detail.show');
Route::get('/detail-informasi/baca/{slug}', [DaftarInformasiDetailController::class, 'detailPage'])->name('detail.read');

// download file 
Route::get('/informasi/download/{slug}', [DaftarInformasiDetailController::class, 'downloadFile'])->name('informasi.download');



// cetak informasi routes
Route::get('/cetak-informasi/laporan', [CetakInformasiController::class, 'print'])
    ->name('cetak.informasi.laporan');

Route::get('/cetak-informasi/pdf', [CetakInformasiController::class, 'downloadPdf'])
    ->name('cetak.informasi.pdf');

Route::get('/cetak-informasi/excel', [CetakInformasiController::class, 'downloadExcel'])
    ->name('cetak.informasi.excel');

