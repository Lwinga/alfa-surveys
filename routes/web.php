<?php

use App\Http\Controllers\ProfilePhotoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return Inertia::render('Profile');
    })->name('profile');

    Route::post('/user/profile-photo', [ProfilePhotoController::class, 'store'])
        ->name('user.photo');

    Route::delete('/user/profile-photo', [ProfilePhotoController::class, 'destroy']);
});
