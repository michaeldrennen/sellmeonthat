<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WantController;
use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('socialite.redirect');
Route::get('/login/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])->name('socialite.callback');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //// Routes that require a user to be logged in
    //Route::get('/dashboard', function () {
    //    return view('dashboard');
    //})->name('dashboard');

    Route::resource('wants', \App\Http\Controllers\WantController::class);
    Route::resource('wants.offers', OfferController::class)->shallow();
    Route::patch('/offers/{offer}/accept', [OfferController::class, 'accept'])->name('offers.accept');
});

require __DIR__.'/auth.php';
