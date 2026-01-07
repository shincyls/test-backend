<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MemberController;
use App\Http\Controllers\Api\V1\BookingController;

// API v1 Routes
Route::prefix('v1/web')->group(function () {

    // Auth routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
    });

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/user', [AuthController::class, 'user']);

        // Members
        Route::get('/members', [MemberController::class, 'index']);
        Route::post('/members', [MemberController::class, 'store']);
        Route::get('/members/{id}', [MemberController::class, 'show']);
        Route::put('/members/{id}', [MemberController::class, 'update']);
        Route::delete('/members/{id}', [MemberController::class, 'destroy']);
        Route::get('/members/{id}/bookings', [MemberController::class, 'bookings']);

        // Bookings
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/bookings/{id}', [BookingController::class, 'show']);
        Route::put('/bookings/{id}', [BookingController::class, 'update']);
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
    });
});
