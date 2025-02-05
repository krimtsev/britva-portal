<?php

use App\Http\Controllers\Teams\TeamsController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'teams', 'middleware' => ["campaign.britva"]], function () {
    Route::get('/', [TeamsController::class, 'index'])->name('p.teams.index');

    Route::get('/create', [TeamsController::class, 'create'])->name('p.teams.create');
    Route::post('/', [TeamsController::class, 'store'])->name('p.teams.store');

    Route::get('/{team}/edit', [TeamsController::class, 'edit'])->name('p.teams.edit');
    Route::patch('/{team}', [TeamsController::class, 'update'])->name('p.teams.update');
    Route::get('/{team}', function () { return redirect()->route('p.teams.index'); });

    Route::delete('/{team}', [TeamsController::class, 'destroy'])->name('p.teams.delete');
});
