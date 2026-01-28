<?php

namespace App\Http\Controllers\LayananInformasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CetakInformasiUserController extends Controller
{
    //
    public function index()
    {
        return view('content.layanan-informasi.cetak-informasi');
    }
}
