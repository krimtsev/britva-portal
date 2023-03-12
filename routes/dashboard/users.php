<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\IndexController;
use App\Http\Controllers\User\CreateController;
use App\Http\Controllers\User\StoreController;
use App\Http\Controllers\User\ShowController;
use App\Http\Controllers\User\EditController;
use App\Http\Controllers\User\UpdateController;
use App\Http\Controllers\User\DestroyController;

Route::group(['middleware' => ['auth', 'isAdmin'], 'prefix' => 'dashboard'], function () {

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [IndexController::class, '__invoke'])->name('d.user.index');
        Route::get('/create', [CreateController::class, '__invoke'])->name('d.user.create');
        Route::post('/', [StoreController::class, '__invoke'])->name('d.user.store');
        Route::get('/{user}', [ShowController::class, '__invoke'])->name('d.user.show');
        Route::get('/{user}/edit', [EditController::class, '__invoke'])->name('d.user.edit');
        Route::patch('/{user}', [UpdateController::class, '__invoke'])->name('d.user.update');
        // Route::delete('/{user}', [DestroyController::class, '__invoke'])->name('d.user.delete');
    });

});
