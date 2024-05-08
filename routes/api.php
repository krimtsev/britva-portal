<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Mango\MangoController;
use App\Http\Controllers\Api\Yclients\YclientsController;
use App\Utils\Telnums;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['api_token']], function () {
    Route::get('/mango', [MangoController::class, 'index']);
    Route::get('/mango/test', [MangoController::class, 'test']);
    Route::get('/mango/statistics', [MangoController::class, 'getStatisticsForPastDay']);
    Route::get('/mango/partners-list', [Telnums::class, 'getPartnersList']);

    Route::get('/yclients/lost-clients', [YclientsController::class, 'getLostClients']);
    Route::get('/yclients/new-clients', [YclientsController::class, 'getNewClients']);
});
