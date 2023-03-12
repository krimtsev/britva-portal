<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post\IndexController;
use App\Http\Controllers\Post\CreateController;
use App\Http\Controllers\Post\StoreController;
use App\Http\Controllers\Post\ShowController;
use App\Http\Controllers\Post\EditController;
use App\Http\Controllers\Post\UpdateController;
use App\Http\Controllers\Post\DestroyController;

Route::group(['middleware' => ['auth', 'isAdmin'], 'prefix' => 'dashboard'], function () {

    Route::group(['prefix' => 'posts'], function () {
        Route::get('/', [IndexController::class, '__invoke'])->name('d.post.index');
        Route::get('/create', [CreateController::class, '__invoke'])->name('d.post.create');
        Route::post('/', [StoreController::class, '__invoke'])->name('d.post.store');
        Route::get('/{post}', [ShowController::class, '__invoke'])->name('d.post.show');
        Route::get('/{post}/edit', [EditController::class, '__invoke'])->name('d.post.edit');
        Route::patch('/{post}', [UpdateController::class, '__invoke'])->name('d.post.update');
        // Route::delete('/{post}', [DestroyController::class, '__invoke'])->name('d.post.delete');
    });

});
