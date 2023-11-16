<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Analytics\IndexController;
use App\Http\Controllers\Analytics\ShowController;
use App\Http\Controllers\Analytics\ChartCompanyController;

Route::group(['middleware' => ['auth', 'isAdmin'], 'prefix' =>  'dashboard'], function () {

    Route::group(['prefix' => 'analytics'], function () {
        Route::get('/', [IndexController::class, '__invoke'])->name('d.analytics.index');

        Route::get('/show', function () { return redirect()->route('d.analytics.index'); });
        Route::post('/show', [ShowController::class, '__invoke'])->name('d.analytics.show');

        Route::get('/company', function () { return redirect()->route('d.analytics.index'); });
        Route::post('/company', [ChartCompanyController::class, '__invoke'])->name('d.analytics.company');
    });

});
