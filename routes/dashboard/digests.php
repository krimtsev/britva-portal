<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Digest\IndexController;
use App\Http\Controllers\Digest\CreateController;
use App\Http\Controllers\Digest\StoreController;
use App\Http\Controllers\Digest\ShowController;
use App\Http\Controllers\Digest\EditController;
use App\Http\Controllers\Digest\UpdateController;
// use App\Http\Controllers\Digest\DestroyController;


Route::group(['prefix' => 'digests'], function () {
    Route::get('/', [IndexController::class, '__invoke'])->name('d.digest.index');
    Route::get('/create', [CreateController::class, '__invoke'])->name('d.digest.create');
    Route::post('/', [StoreController::class, '__invoke'])->name('d.digest.store');
    Route::get('/{digest}', [ShowController::class, '__invoke'])->name('d.digest.show');
    Route::get('/{digest}/edit', [EditController::class, '__invoke'])->name('d.digest.edit');
    Route::patch('/{digest}', [UpdateController::class, '__invoke'])->name('d.digest.update');
    // Route::delete('/{digest}', [DestroyController::class, '__invoke'])->name('d.digest.delete');
});
