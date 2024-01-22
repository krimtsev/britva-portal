<?php

use App\Http\Controllers\Royalty\IndexController;
use App\Http\Controllers\Royalty\ShowController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'royalty'], function () {
    Route::get('/', [IndexController::class, '__invoke'])->name('d.royalty.index');

    Route::get('/show', function () { return redirect()->route('d.royalty.index'); });
    Route::post('/show', [ShowController::class, '__invoke'])->name('d.royalty.show');

});
