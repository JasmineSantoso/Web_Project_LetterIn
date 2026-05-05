<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function notifications()
    {
        return view('social.notifications');
    }

    public function bookmates()
    {
        return view('social.bookmates');
    }
}
