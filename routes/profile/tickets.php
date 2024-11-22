<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tickets\TicketsController;
use App\Http\Controllers\Tickets\TicketsCategoriesController;

Route::group(['prefix' => 'tickets'], function () {
    Route::get('/', [TicketsController::class, 'index'])->name('p.tickets.index');
    Route::get('/create/{topic?}', [TicketsController::class, 'create'])->name('p.tickets.create-template');
    Route::get('/create', [TicketsController::class, 'create'])->name('p.tickets.create');
    Route::post('/{topic?}', [TicketsController::class, 'store'])->name('p.tickets.store');
    // Route::get('/{sheet}', [ShowController::class, 'show'])->name('d.tickets.show');
    Route::get('/{ticket}/edit', [TicketsController::class, 'edit'])->name('p.tickets.edit');
    Route::patch('/{ticket}/message', [TicketsController::class, 'updateMessage'])->name('p.tickets.update-message');
    Route::post('/{ticket}/state', [TicketsController::class, 'state'])->name('p.ticket.state');
});
