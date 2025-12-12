<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\PropertyApiController;

use App\Http\Controllers\Api\AdminApiController;


Route::prefix('admin')->group(function () {
    Route::get('/properties', [AdminApiController::class, 'indexProperties']);
    Route::get('/properties/stats', [AdminApiController::class, 'stats']); // <-- stats API
    Route::post('/properties', [AdminApiController::class, 'storeProperty']);
    Route::put('/properties/{id}', [AdminApiController::class, 'updateProperty']);
    Route::delete('/properties/{id}', [AdminApiController::class, 'destroyProperty']);

    Route::get('/bookings', [AdminApiController::class, 'indexBookings']);
    Route::delete('/bookings/{id}', [AdminApiController::class, 'destroyBooking']);
});


Route::prefix('properties')->group(function () {
    Route::get('/search', [PropertyApiController::class, 'search']);  // MUST be first
    Route::get('/', [PropertyApiController::class, 'index']);
    Route::get('/{id}', [PropertyApiController::class, 'show']);
});


Route::prefix('reviews')->group(function () {
    Route::get('/{propertyId}', [ReviewApiController::class, 'index']);  // Get reviews for property
    Route::post('/', [ReviewApiController::class, 'store']);             // Post a new review
});

// Public route (optional if you allow guest bookings)
Route::post('/bookings', [BookingApiController::class, 'store']);


    Route::get('/bookings', [BookingApiController::class, 'index']);
   Route::delete('/bookings/{id}', [BookingApiController::class, 'destroy']);
    Route::delete('/bookings/item/{itemId}', [BookingApiController::class, 'destroyItem']);


   
