<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Analytics\IndexController;
use App\Http\Controllers\Analytics\ShowController;
use App\Http\Controllers\Analytics\ChartCompanyController;
use App\Http\Controllers\Analytics\ChartStaffController;

Route::group(['middleware' => ['auth'], 'prefix' =>  'profile'], function () {

    Route::group(['prefix' => 'analytics'], function () {
        Route::get('/', [IndexController::class, '__invoke'])->name('p.analytics.index');

        Route::get('/show', function () { return redirect()->route('p.analytics.index'); });
        Route::post('/show', [ShowController::class, '__invoke'])->name('p.analytics.show');

        Route::get('/company/chart', function () { return redirect()->route('p.analytics.index'); });
        Route::post('/company/chart', [ChartCompanyController::class, '__invoke'])->name('p.analytics.company');

        Route::get('/staff/chart', function () { return redirect()->route('p.analytics.index'); });
        Route::post('/staff/chart', [ChartStaffController::class, '__invoke'])->name('p.analytics.staff');
    });

});
