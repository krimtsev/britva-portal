<?php

use App\Http\Controllers\Analytics\ClientsVisitsController;
use App\Http\Controllers\Jobs\ClientsVisits;
use App\Http\Controllers\Mango\MangoController;
use App\Utils\Telnums;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api_token']], function () {
    Route::get('/mango', [MangoController::class, 'index']);
    Route::get('/mango/test', [MangoController::class, 'test']);
    Route::get('/mango/statistics', [MangoController::class, 'getStatisticsForPastDay']);
    Route::get('/mango/partners-list', [Telnums::class, 'getPartnersList']);

    Route::get('/lost-clients', [ClientsVisitsController::class, 'getLostClients']);
    Route::get('/new-clients', [ClientsVisitsController::class, 'getNewClients']);

    Route::group(['prefix' => 'jobs'], function () {
        Route::get('clients-visits', [ClientsVisits::class, 'index']);
    });
});
