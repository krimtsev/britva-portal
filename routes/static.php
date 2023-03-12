<?php
use Illuminate\Support\Facades\Route;

Route::get('/discounts', function () {
    return view('static.discounts');
})->name('static.discounts');

Route::get('/food-cooperative', function () {
    return view('static.food-cooperative');
});

Route::get('/contact-franchise', function () {
    return view('static.contact-franchise');
});
