<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use Utils;

class IndexController extends Controller
{
    public function __invoke()
    {
        $months = Utils::getMonthArray();

        return view('profile.analitics.index', compact('months'));
    }
}
