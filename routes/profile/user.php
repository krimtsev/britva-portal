<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::get('/change-password', [ProfileController::class, 'changePasswordIndex'])->name('p.user.password.index');
Route::patch('/change-password', [ProfileController::class, 'changePasswordUpdate'])->name('p.user.password.update');
