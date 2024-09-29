<?php

use App\Http\Controllers\Analytics\ClientsVisitsController;
use App\Http\Controllers\Mango\MangoController;
use App\Http\Controllers\Staff\StaffController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api_token']], function () {
    Route::get('/mango', [MangoController::class, 'index']);
    Route::get('/mango/test', [MangoController::class, 'test']);
    Route::get('/mango/statistics', [MangoController::class, 'getStatisticsForPastDay']);
    Route::get('/mango/blacklist', [MangoController::class, 'updateBlacklist']);
    Route::get('/mango/check-number/{number}', [MangoController::class, 'checkNumberInBlacklist']);

    Route::get('/lost-clients', [ClientsVisitsController::class, 'getLostClients']);
    Route::get('/new-clients', [ClientsVisitsController::class, 'getNewClients']);
    Route::get('/repeat-clients', [ClientsVisitsController::class, 'getRepeatClients']);

    Route::get('/telnums-list', [MangoController::class, 'getTelnumsList']);
});

Route::post('/staff', [StaffController::class, 'index']);
