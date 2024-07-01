<?php

use App\Http\Controllers\Analytics\ClientsVisitsController;
use App\Http\Controllers\Jobs\ClientsVisits;
use App\Http\Controllers\Mango\MangoController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Test\TestController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api_token']], function () {
    Route::get('/mango', [MangoController::class, 'index']);
    Route::get('/mango/test', [MangoController::class, 'test']);
    Route::get('/mango/statistics', [MangoController::class, 'getStatisticsForPastDay']);

    Route::get('/lost-clients', [ClientsVisitsController::class, 'getLostClients']);
    Route::get('/new-clients', [ClientsVisitsController::class, 'getNewClients']);
    Route::get('/repeat-clients', [ClientsVisitsController::class, 'getRepeatClients']);

    Route::group(['prefix' => 'jobs'], function () {
        Route::get('clients-visits', [ClientsVisits::class, 'index']);
    });

    // Тестовые роуты
    Route::get('/update-telnums', [TestController::class, 'updateTelnums']);
});

Route::post('/staff', [StaffController::class, 'index']);
