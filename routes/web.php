<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\ReviewController;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

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
Route::get('/book/{book_id}/review', [ReviewController::class, 'create'])->name('book.review');
Route::post('/book/{book_id}/review', [ReviewController::class, 'store'])->name('book.review.store');

// Profile & Settings
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::get('/notifications', [SocialController::class, 'notifications'])->name('notifications');
    Route::get('/bookmates', [SocialController::class, 'bookmates'])->name('bookmates');
});

// Public Social
Route::get('/profile/{id}', [ProfileController::class, 'friendsProfile'])->name('profile.friend');
