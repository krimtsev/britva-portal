<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Statements\StatementsController;
use App\Http\Controllers\Statements\StatementsCategoriesController;

Route::group(['prefix' => 'statements'], function () {
    Route::get('/', [StatementsController::class, 'index'])->name('d.statements.index');
    Route::get('/create', [StatementsController::class, 'create'])->name('d.statements.create');
    Route::post('/', [StatementsController::class, 'store'])->name('d.statements.store');
    // Route::get('/{sheet}', [ShowController::class, 'show'])->name('d.statements.show');
    Route::get('/{statement}/edit', [StatementsController::class, 'edit'])->name('d.statements.edit');
    Route::patch('/{statement}', [StatementsController::class, 'update'])->name('d.statements.update');
    Route::patch('/{statement}/message', [StatementsController::class, 'updateMessage'])->name('d.statements.update-message');
    // Route::delete('/{sheet}', [DestroyController::class, 'delete'])->name('d.statements.delete');
});

Route::group(['prefix' => 'statements-categories'], function () {
    Route::get('/', [StatementsCategoriesController::class, 'index'])->name('d.statements-categories.index');
    Route::get('/create', [StatementsCategoriesController::class, 'create'])->name('d.statements-categories.create');
    Route::post('/', [StatementsCategoriesController::class, 'store'])->name('d.statements-categories.store');
    Route::get('/{category}/edit', [StatementsCategoriesController::class, 'edit'])->name('d.statements-categories.edit');
    Route::patch('/{category}', [StatementsCategoriesController::class, 'update'])->name('d.statements-categories.update');
    // Route::delete('/{sheet}', [DestroyController::class, 'delete'])->name('d.sheet.delete');
});
