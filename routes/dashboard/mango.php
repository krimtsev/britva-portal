<?php

use App\Http\Controllers\Mango\BlackListController;
use App\Http\Controllers\Mango\IndexController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'mango'], function () {
    Route::get('/', [IndexController::class, 'index']);
    Route::get('/{date}', [IndexController::class, 'index']);
});

Route::group(['prefix' => 'blacklist'], function () {
    Route::get('/', [BlackListController::class, 'index'])->name('d.blacklist.index');
    Route::get('/{blacklist}/edit', [BlackListController::class, 'edit'])->name('d.blacklist.edit');
    Route::patch('/{blacklist}', [BlackListController::class, 'update'])->name('d.blacklist.update');
});

