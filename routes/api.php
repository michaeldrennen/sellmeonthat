<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusinessProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\WantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API v1 routes
Route::prefix('v1')->group(function () {

    // Authentication routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

        // Wants
        Route::apiResource('wants', WantController::class);

        // Offers (nested under wants for creation)
        Route::post('/wants/{want}/offers', [OfferController::class, 'store']);
        Route::apiResource('offers', OfferController::class)->except(['store']);
        Route::patch('/offers/{offer}/accept', [OfferController::class, 'accept']);

        // Business Profiles
        Route::apiResource('business-profiles', BusinessProfileController::class);

        // Conversations & Messages
        Route::apiResource('conversations', ConversationController::class)->only(['index', 'show', 'store']);
        Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
        Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
        Route::patch('/messages/{message}/read', [MessageController::class, 'markAsRead']);

        // User-specific routes
        Route::get('/profile/wants', function (Request $request) {
            return \App\Http\Resources\WantResource::collection(
                $request->user()->wants()->with(['categories', 'offers'])->latest()->paginate(15)
            );
        });

        Route::get('/profile/offers', function (Request $request) {
            $businessProfile = $request->user()->businessProfile;
            if (!$businessProfile) {
                return response()->json(['message' => 'No business profile found'], 404);
            }
            return \App\Http\Resources\OfferResource::collection(
                $businessProfile->offers()->with(['want', 'want.user'])->latest()->paginate(15)
            );
        });
    });

    // Public routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    // Public business profiles
    Route::get('/businesses', [BusinessProfileController::class, 'index']);
    Route::get('/businesses/{businessProfile}', [BusinessProfileController::class, 'show']);

    // Public wants (for businesses to browse)
    Route::get('/wants', [WantController::class, 'index']);
    Route::get('/wants/{want}', [WantController::class, 'show']);
});
