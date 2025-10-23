<?php

use Illuminate\Support\Facades\Route;

//authentication pages
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    return redirect('/login');
})->name('logout');

//landing page
Route::get('/', function () {
    return view('landing');
});

// Property listing in a card
Route::get('/properties', function () {
    return view('property'); 
})->name('properties.index');

//single property detail page
Route::get('/properties/{id}', function ($id) {
    return view('show', ['id' => $id]); 
})->name('properties.show');

//checkout page

Route::get('/checkout/{id}', function ($id) {
    return view('booking', ['id' => $id]); 
})->name('checkout');

//all user booking routes
Route::get('/bookings', function () {
    return view('showbooking');
})->name('bookings.index');

//all admin routes
Route::get('/admin/dashboard', function () {
    return view('admin.admindashboard');
})->name('admin.dashboard');

Route::get('/admin/bookings', function () {
    return view('admin.adminbookings');
})->name('admin.bookings');

Route::get('/admin/propertycreate', function () {
    return view('admin.property_form');
});

Route::get('/admin/propertyedit/{id}', function ($id) {
    return view('admin.property_edit', ['id' => $id]);
});
