<?php

namespace App\Http\Controllers\Analytics;

use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Utils\Utils;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $months = Utils::getMonthArray();

        $selected_month = array_keys($months)[0];

        $partners = [];

        $selected_partner = "";

        if (Auth::user()->isUser()) {
            $partner_id = Auth::user()->partner_id;

            if (!$partner_id) {
                $company_id_not_found = true;
                return view("analytics.index", compact("company_id_not_found"));
            }

            $partner = Partner::select("yclients_id")->where('id', $partner_id)->first();
            if ($partner->yclients_id) {
                $selected_partner = $partner->yclients_id;
            } else {
                $company_id_not_found = true;

                // возвращаем ошибку если ID партнера не найден
                return view("analytics.index", compact("company_id_not_found"));
            }
        } else {
            $partners = Partner::select("name", "yclients_id")
                ->where('yclients_id', '<>', "")
                ->where('disabled', '<>', 1)
                ->orderBy("name")->get();
            $selected_partner = $partners[0]->yclients_id;
        }

        list("isDashboard" => $isDashboard) = RouteServiceProvider::getAdminTypeView();

        return view("analytics.index", compact(
            "months",
            "selected_month",
            "partners",
            "selected_partner",
            "isDashboard",
        ));
    }
}

