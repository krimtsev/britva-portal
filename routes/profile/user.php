<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::get('/', [ProfileController::class, 'index'])->name('p.user.index');
Route::patch('/', [ProfileController::class, 'update'])->name('p.user.password.update');
