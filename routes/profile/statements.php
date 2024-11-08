<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Statements\StatementsController;
use App\Http\Controllers\Statements\StatementsCategoriesController;

Route::group(['prefix' => 'statements'], function () {
    Route::get('/', [StatementsController::class, 'index'])->name('p.statements.index');
    Route::get('/create', [StatementsController::class, 'create'])->name('p.statements.create');
    Route::post('/', [StatementsController::class, 'store'])->name('p.statements.store');
    // Route::get('/{sheet}', [ShowController::class, 'show'])->name('d.statements.show');
    Route::get('/{statement}/edit', [StatementsController::class, 'edit'])->name('p.statements.edit');
    Route::patch('/{statement}', [StatementsController::class, 'update'])->name('p.statements.update');
    Route::post('/{statement}/state', [StatementsController::class, 'state'])->name('p.statement.state');
});
