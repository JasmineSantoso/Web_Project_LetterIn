<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function settings()
    {
        return view('profile.settings');
    }

    public function friendsProfile($id)
    {
        return view('profile.friend', compact('id'));
    }
}
