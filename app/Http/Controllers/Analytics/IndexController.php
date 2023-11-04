<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\Utils;

class IndexController extends Controller
{
    public function __invoke()
    {
        $months = Utils::getMonthArray();

        $users = User::select('login', 'name', 'yclients_id')->orderBy('id', 'DESC')->get();

        return view('analytics.index', compact("months", "users"));
    }
}

