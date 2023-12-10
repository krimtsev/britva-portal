<?php

namespace App\Http\Controllers\Analytics;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Utils\Utils;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {

        // $request->route()->getAction("view") === "dashboard";

        $months = Utils::getMonthArray();

        $selected_month = array_keys($months)[0];

        $users = [];

        $selected_user = "";
        if (Auth::user()->isUser()) {
            $selected_user = Auth::user()->yclients_id;
        } else {
            $users = User::select("name", "yclients_id")->where('yclients_id', '<>', "")->orderBy("name")->get();
            $selected_user = $users[0]->yclients_id;
        }

        list("isDashboard" => $isDashboard) = RouteServiceProvider::getAdminTypeView();

        return view("analytics.index", compact(
            "months",
            "selected_month",
            "users",
            "selected_user",
            "isDashboard"
        ));
    }
}

