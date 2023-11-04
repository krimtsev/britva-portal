<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::group(['middleware' => ['auth'], 'prefix' => 'profile'], function () {

    Route::get('/', [ProfileController::class, '__invoke'])->name('p.home.index');

});
