<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\StatusCompanyController;


Route::group(['prefix' => 'jobs'], function () {
    Route::get('/', function () { return redirect()->route('d.jobs.status-company'); });

    Route::get('/status-company', [StatusCompanyController::class, '__invoke'])->name('d.jobs.status-company');
});
