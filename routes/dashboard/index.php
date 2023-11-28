<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


Route::get('/', [DashboardController::class, '__invoke'])->name('d.home.index');
