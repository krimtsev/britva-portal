<?php

use App\Http\Controllers\Partners\PartnersController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/discounts', function () {
        return view('static.discounts');
    })->name('static.discounts');

    Route::get('/food-cooperative', function () {
        return view('static.food-cooperative');
    });

    Route::get('/contact-franchise', [PartnersController::class, 'contacts'])->name('s.contact-franchise');

	Route::get('/internet-calculator', function () {
        return view('static.internet-calculator');
    });
});
