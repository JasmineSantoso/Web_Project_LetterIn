<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showSignup()
    {
        return view('auth.auth', ['mode' => 'signup']);
    }

    public function processSignup(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',      // minimal satu huruf besar
                'regex:/[0-9]/',      // minimal satu angka
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // minimal satu simbol
            ],
        ], [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah terdaftar',
            'password.regex' => 'Password min 8 karakter + huruf besar + angka + simbol',
        ]);

        $user = User::create([
            'fullname' => $validated['fullname'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->intended('/'); // Redirect ke home page
    }

    public function showSignin()
    {
        return view('auth.auth', ['mode' => 'signin']);
    }

    public function processSignin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if the user is banned
        $banned = \App\Models\BannedUser::where('email', $credentials['email'])->first();
        if ($banned) {
            return back()->withErrors([
                'email' => 'Akun Anda telah ditangguhkan karena: ' . $banned->ban_reason,
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if (isset($user->is_admin) && $user->is_admin) {
                return redirect()->intended('/admin/dashboard');
            }
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
