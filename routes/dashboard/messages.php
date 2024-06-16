<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Messages\MessagesController;


Route::group(['prefix' => 'messages'], function () {
    Route::get('/', [MessagesController::class, 'index'])->name('d.messages.index');
    Route::post('/', [MessagesController::class, 'send'])->name('d.messages.send');
});
