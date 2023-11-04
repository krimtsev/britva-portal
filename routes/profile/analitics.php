<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Analytics\IndexController;

Route::group(['middleware' => ['auth'], 'prefix' => 'profile'], function () {

    Route::group(['prefix' => 'analytics'], function () {
        Route::get('/', [IndexController::class, '__invoke'])->name('p.analytics.index');
        Route::get('/show', [IndexController::class, '__invoke'])->name('p.analytics.show');
    });

});
