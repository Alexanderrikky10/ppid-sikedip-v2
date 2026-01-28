<?php

namespace App\Http\Controllers\LayananInformasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TataCaraController extends Controller
{
    //

    public function index()
    {
        return view('content.layanan-informasi.tatacara');
    }
}
