<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MissedCalls\MissedCallsController;


Route::group(['prefix' => 'missed-calls'], function () {
    Route::get('/', [MissedCallsController::class, 'index'])->name('d.missed-calls.index');
    Route::get('/{partner}/edit', [MissedCallsController::class, 'edit'])->name('d.missed-calls.edit');
    Route::patch('/{partner}', [MissedCallsController::class, 'update'])->name('d.missed-calls.update');
});
