<?php

use App\Http\Controllers\Partners\PartnersController;
use Illuminate\Support\Facades\Route;

Route::get('/partners', [PartnersController::class, 'partnerIndex'])->name('p.partners.index');
