<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Page\IndexController;
use App\Http\Controllers\Page\CreateController;
use App\Http\Controllers\Page\StoreController;
use App\Http\Controllers\Page\ShowController;
use App\Http\Controllers\Page\EditController;
use App\Http\Controllers\Page\UpdateController;
use App\Http\Controllers\Page\DestroyController;

Route::group(['middleware' => ['auth', 'isAdmin'], 'prefix' => 'dashboard'], function () {

    Route::group(['prefix' => 'pages'], function () {
        Route::get('/', [IndexController::class, '__invoke'])->name('d.page.index');
        Route::get('/create', [CreateController::class, '__invoke'])->name('d.page.create');
        Route::post('/', [StoreController::class, '__invoke'])->name('d.page.store');
        Route::get('/{page}', [ShowController::class, '__invoke'])->name('d.page.show');
        Route::get('/{page}/edit', [EditController::class, '__invoke'])->name('d.page.edit');
        Route::patch('/{page}', [UpdateController::class, '__invoke'])->name('d.page.update');
        // Route::delete('/{page}', [DestroyController::class, '__invoke'])->name('d.page.delete');
    });

});
