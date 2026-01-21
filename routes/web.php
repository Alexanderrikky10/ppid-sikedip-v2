<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cetak\CetakInformasiController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\WelcomePage\WelcomePageController::class, 'welcome'])
    ->name('welcome.page');

Route::get('/cetak-informasi/laporan', [CetakInformasiController::class, 'print'])
    ->name('cetak.informasi.laporan');

Route::get('/cetak-informasi/pdf', [CetakInformasiController::class, 'downloadPdf'])
    ->name('cetak.informasi.pdf');

Route::get('/cetak-informasi/excel', [CetakInformasiController::class, 'downloadExcel'])
    ->name('cetak.informasi.excel');
