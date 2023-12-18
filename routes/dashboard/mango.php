<?php

use App\Http\Controllers\Mango\IndexController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'mango'], function () {
    Route::get('/', [IndexController::class, '__invoke']);
    Route::get('/{date}', [IndexController::class, '__invoke']);
});
