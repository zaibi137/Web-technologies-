<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Token Required)
|--------------------------------------------------------------------------
*/
// Authentication Handles
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Publicly Viewable Hotel Data
Route::get('/hotels', [HotelController::class, 'index']);
Route::get('/hotels/{hotel}', [HotelController::class, 'show']);

// Publicly Viewable Room Data
Route::get('/hotels/{hotel_id}/rooms', [RoomController::class, 'indexByHotel']);
Route::get('/rooms/{room}', [RoomController::class, 'show']);
Route::get('/rooms/{room}/availability', [RoomController::class, 'checkAvailability']);

// Publicly Viewable Reviews
Route::get('/hotels/{hotel_id}/reviews', [ReviewController::class, 'indexByHotel']);


/*
|--------------------------------------------------------------------------
| Protected Routes (Requires a Valid Bearer Token via Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Authenticated User Profile Management
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Bookings (Standard users can read/create)
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);

    // Reviews Writes
    Route::post('/hotels/{hotel_id}/reviews', [ReviewController::class, 'store']); 

    /*
    |--------------------------------------------------------------------------
    | Admin-Only Moderation & Management Routes
    |--------------------------------------------------------------------------
    | Placed inside a single group so that endpoints cleanly match your frontend 
    | utility logic patterns while remaining under lock and key.
    |
    | Note: Removed the nested 'admin' prefix from users and dashboard so they 
    | line up perfectly with your javascript apiRequest paths: 
    | '/users' and '/admin/dashboard'
    |
    */
    Route::middleware('admin')->group(function () {
        
        // Hotel Management Write Actions
        Route::post('/hotels', [HotelController::class, 'store']);
        Route::put('/hotels/{hotel}', [HotelController::class, 'update']);
        Route::delete('/hotels/{hotel}', [HotelController::class, 'destroy']);

        // Room Management Write Actions
        Route::post('/rooms', [RoomController::class, 'store']);
        Route::put('/rooms/{room}', [RoomController::class, 'update']);
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy']);

        // Booking Status Control
        Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus']);

        // Review Moderation
        Route::get('/reviews', [ReviewController::class, 'index']);
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
        Route::patch('/reviews/{review}/toggle-visibility', [ReviewController::class, 'toggleVisibility']);

        // Users Management System (Matches apiRequest('/admin/users'))
        Route::get('/admin/users', [UserController::class, 'index']);
        Route::get('/admin/users/{user}', [UserController::class, 'show']);
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy']);
        Route::patch('/admin/users/{user}/role', [UserController::class, 'updateRole']);
        Route::patch('/admin/users/{user}/toggle-active', [UserController::class, 'toggleActive']);

        // Dashboard Metrics Context (Matches apiRequest('/admin/dashboard'))
        Route::get('/admin/dashboard', [DashboardController::class, 'stats']);
    });
});