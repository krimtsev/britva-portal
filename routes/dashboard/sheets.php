<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sheet\IndexController;
use App\Http\Controllers\Sheet\CreateController;
use App\Http\Controllers\Sheet\StoreController;
use App\Http\Controllers\Sheet\ShowController;
use App\Http\Controllers\Sheet\EditController;
use App\Http\Controllers\Sheet\UpdateController;
use App\Http\Controllers\Sheet\DestroyController;

Route::group(['middleware' => ['auth', 'isAdmin'], 'prefix' => 'dashboard'], function () {

    Route::group(['prefix' => 'sheets'], function () {
        Route::get('/', [IndexController::class, '__invoke'])->name('d.sheet.index');
        Route::get('/create', [CreateController::class, '__invoke'])->name('d.sheet.create');
        Route::post('/', [StoreController::class, '__invoke'])->name('d.sheet.store');
        Route::get('/{sheet}', [ShowController::class, '__invoke'])->name('d.sheet.show');
        Route::get('/{sheet}/edit', [EditController::class, '__invoke'])->name('d.sheet.edit');
        Route::patch('/{sheet}', [UpdateController::class, '__invoke'])->name('d.sheet.update');
        // Route::delete('/{sheet}', [DestroyController::class, '__invoke'])->name('d.sheet.delete');
    });
});
