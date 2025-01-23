<?php

use App\Http\Controllers\Teams\TeamsController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'teams'], function () {
    Route::get('/', [TeamsController::class, 'index'])->name('d.teams.index');

    Route::get('/create', [TeamsController::class, 'create'])->name('d.teams.create');
    Route::post('/', [TeamsController::class, 'store'])->name('d.teams.store');

    Route::get('/{team}/edit', [TeamsController::class, 'edit'])->name('d.teams.edit');
    Route::patch('/{team}', [TeamsController::class, 'update'])->name('d.teams.update');
    Route::get('/{team}', function () { return redirect()->route('d.teams.index'); });

    Route::delete('/{team}', [TeamsController::class, 'destroy'])->name('d.teams.delete');
});
