<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Partners\PartnersController;



Route::group(['prefix' => 'partners'], function () {
    Route::get('/', [PartnersController::class, 'index'])->name('d.partner.index');

    Route::get('/create', [PartnersController::class, 'create'])->name('d.partner.create');
    Route::post('/', [PartnersController::class, 'store'])->name('d.partner.store');

    Route::get('/{partner}/edit', [PartnersController::class, 'edit'])->name('d.partner.edit');
    Route::patch('/{partner}', [PartnersController::class, 'update'])->name('d.partner.update');

    Route::delete('/{partner}', [PartnersController::class, 'destroy'])->name('d.partner.delete');

    // Выполнялось для заполнения телефонов по списку
    // Route::get('/update-telnums', [PartnersController::class, 'updateTelnumsByJSONList']);
});
