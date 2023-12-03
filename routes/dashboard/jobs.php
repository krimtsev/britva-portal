<?php

use App\Http\Controllers\StatusCompanyController;
use App\Http\Controllers\Jobs\IndexController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'jobs'], function () {
    Route::get('/', function () { return redirect()->route('d.jobs.status-company'); });
    Route::get('/status-company', [StatusCompanyController::class, '__invoke'])->name('d.jobs.status-company');
    Route::get('/start', [IndexController::class, '__invoke'])->name('d.jobs.status');
});
