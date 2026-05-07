<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialController;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'processSignup']);
Route::get('/signin', [AuthController::class, 'showSignin'])->name('signin');
Route::post('/signin', [AuthController::class, 'processSignin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Book Routes
Route::get('/browse', [BookController::class, 'browse'])->name('browse');
Route::get('/book/{id}', [BookController::class, 'details'])->name('book.details');
Route::get('/search', [BookController::class, 'search'])->name('search');
Route::get('/book/{id}/review', [BookController::class, 'addReview'])->name('book.review');

// Profile & Settings
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::get('/notifications', [SocialController::class, 'notifications'])->name('notifications');
    Route::get('/bookmates', [SocialController::class, 'bookmates'])->name('bookmates');
});

// Public Social
Route::get('/profile/{id}', [ProfileController::class, 'friendsProfile'])->name('profile.friend');
