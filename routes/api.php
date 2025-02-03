<?php

use App\Http\Controllers\Analytics\ClientsVisitsController;
use App\Http\Controllers\Mango\MangoController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Api\Teams\ApiTeamsController;
use App\Http\Controllers\Api\Partners\ApiPartnersController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['api.portal'], 'prefix' => 'portal'], function () {
    Route::get('/teams', [ApiTeamsController::class, 'getTeamsList']);
    Route::get('/teams/{partner_id}', [ApiTeamsController::class, 'getTeamsByPartnersId']);
    Route::get('/teams/roles', [ApiTeamsController::class, 'getTeamsRoles']);
    Route::get('/partners', [ApiPartnersController::class, 'getPartnersList']);
});

Route::group(['middleware' => ['api.personal']], function () {
    Route::get('/mango', [MangoController::class, 'index']);
    Route::get('/mango/test', [MangoController::class, 'test']);
    Route::get('/mango/statistics', [MangoController::class, 'getStatisticsForPastDay']);
    Route::get('/mango/blacklist', [MangoController::class, 'updateBlacklist']);
    Route::get('/mango/check-number/{number}', [MangoController::class, 'checkNumberInBlacklist']);

    Route::get('/lost-clients', [ClientsVisitsController::class, 'getLostClients']);
    Route::get('/new-clients', [ClientsVisitsController::class, 'getNewClients']);
    Route::get('/repeat-clients', [ClientsVisitsController::class, 'getRepeatClients']);

    Route::get('/telnums-list', [MangoController::class, 'getTelnumsList']);

    Route::get('/staff-update', [StaffController::class, 'sync']);
});

