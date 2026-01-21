<?php

namespace App\Http\Controllers\WelcomePage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomePageController extends Controller
{
    //
    public function welcome()
    {
        return view('content.welcome-page.welcome');
    }
}
