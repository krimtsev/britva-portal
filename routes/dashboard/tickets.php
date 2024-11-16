<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tickets\TicketsController;
use App\Http\Controllers\Tickets\TicketsCategoriesController;

Route::group(['prefix' => 'tickets'], function () {
    Route::get('/', [TicketsController::class, 'index'])->name('d.tickets.index');
    Route::get('/create', [TicketsController::class, 'create'])->name('d.tickets.create');
    Route::post('/', [TicketsController::class, 'store'])->name('d.tickets.store');
    Route::get('/{ticket}', function () {
        return redirect()->route('d.tickets.index');
    });
    Route::get('/{ticket}/edit', [TicketsController::class, 'edit'])->name('d.tickets.edit');
    Route::patch('/{ticket}', [TicketsController::class, 'update'])->name('d.tickets.update');
    Route::patch('/{ticket}/message', [TicketsController::class, 'updateMessage'])->name('d.tickets.update-message');
    Route::delete('/{ticket}', [TicketsController::class, 'delete'])->name('d.tickets.delete');
});

Route::group(['prefix' => 'tickets-categories'], function () {
    Route::get('/', [TicketsCategoriesController::class, 'index'])->name('d.tickets-categories.index');
    Route::get('/create', [TicketsCategoriesController::class, 'create'])->name('d.tickets-categories.create');
    Route::post('/', [TicketsCategoriesController::class, 'store'])->name('d.tickets-categories.store');
    Route::get('/{ticket}', function () {
        return redirect()->route('d.tickets-categories.index');
    });
    Route::get('/{category}/edit', [TicketsCategoriesController::class, 'edit'])->name('d.tickets-categories.edit');
    Route::patch('/{category}', [TicketsCategoriesController::class, 'update'])->name('d.tickets-categories.update');
    // Route::delete('/{ticket}', [DestroyController::class, 'delete'])->name('d.sheet.delete');
});
