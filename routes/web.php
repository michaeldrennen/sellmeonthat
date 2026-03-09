<?php

use App\Http\Controllers\BusinessProfileController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
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

    // Wants
    Route::resource('wants', WantController::class);

    // Offers
    Route::resource('wants.offers', OfferController::class)->shallow();
    Route::patch('/offers/{offer}/accept', [OfferController::class, 'accept'])->name('offers.accept');

    // Business Profiles
    Route::resource('business-profiles', BusinessProfileController::class);

    // Conversations & Messages
    Route::resource('conversations', ConversationController::class)->only(['index', 'show', 'store']);
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('conversations.messages.store');
});

require __DIR__.'/auth.php';
