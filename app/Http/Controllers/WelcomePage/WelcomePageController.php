<?php

namespace App\Http\Controllers\WelcomePage;

use App\Http\Controllers\Controller;
use App\Models\WelcomePage;

class WelcomePageController extends Controller
{
    public function welcome()
    {
        $welcomePage = WelcomePage::where('is_active', true)->latest()->first()
            ?? WelcomePage::latest()->first();

        return view('content.welcome-page.welcome', compact('welcomePage'));
    }
}