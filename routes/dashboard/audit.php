<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Audit\AuditController;


Route::group(['prefix' => 'audit'], function () {
    Route::get('/', [AuditController::class, 'index'])->name('d.audit.index');
});
