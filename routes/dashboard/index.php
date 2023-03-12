<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::group(['middleware' => ['auth', 'isAdmin'], 'prefix' => 'dashboard'], function () {

    Route::get('/', [DashboardController::class, '__invoke'])->name('d.home.index');

});
