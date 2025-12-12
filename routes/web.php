<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/


// Show login page
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Show register page
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// Process login
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Process register
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PropertyController::class, 'landing'])->name('landing');

// Property listing

Route::get('/properties', [PropertyController::class, 'index'])->name('properties');
// routes/web.php
Route::get('/properties/searched', [PropertyController::class, 'search']);

// Property details
Route::get('/properties/{id}', [PropertyController::class, 'show'])->name('properties.show');
Route::post('/reviews', [ReviewController::class, 'store']);

Route::middleware(['auth'])->group(function() {
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});
// Cart
Route::get('/cart', [PropertyController::class, 'cart'])->name('properties.cart');

// Checkout
Route::get('/checkout', [BookingController::class, 'checkout'])->name('checkout');

Route::get('/bookings', [BookingController::class, 'index']);


// User bookings
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', [AdminController::class, 'index']);
Route::post('/admin/propertystore', [AdminController::class, 'storeProperty'])->name('admin.property.store');
// Admin property delete
Route::delete('/admin/propertydelete/{id}', [PropertyController::class, 'destroy'])->name('property.destroy');
// Admin - Create Property Form
Route::get('/admin/propertycreate', [AdminController::class, 'createProperty'])
    ->name('admin.property.create');


Route::get('/admin/property/{id}/edit', [PropertyController::class, 'edit'])->name('property.edit');
Route::put('/admin/property/{id}', [PropertyController::class, 'update'])->name('property.update');
Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
Route::delete('/admin/bookings/{id}', [AdminController::class, 'destroyBooking'])->name('admin.bookings.destroy');

