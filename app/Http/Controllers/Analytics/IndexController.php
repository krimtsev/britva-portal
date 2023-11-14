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

        $selected_month = array_keys($months)[0];

        $users = User::select('login', 'name', 'yclients_id')->orderBy('name')->get();

        $selected_user = $users[0]->yclients_id;

        return view('analytics.index', compact(
            "months",
            "selected_month",
            "users",
            "selected_user",
        ));
    }
}

