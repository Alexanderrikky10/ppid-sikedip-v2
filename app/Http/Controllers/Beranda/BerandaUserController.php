<?php

namespace App\Http\Controllers\Beranda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BerandaUserController extends Controller
{
    
    //
    public function index()
    {
        return view('content.beranda-user.beranda');
    }
}
