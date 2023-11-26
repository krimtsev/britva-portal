<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Analytics\TableReport;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ShowController extends Controller
{
    public function __invoke(Request $request)
    {
        // Дата окончания (передается из формы)
        $end_date = $request->input("month");

        // Дата начала
        $start_date =  Utils::setFirstDay($end_date);

        // ID филиала
        $company_id = $request->input("company_id");

        // Если ID филиала не указан, берем его у авторизированного пользователя
        if (!$company_id) {
            $company_id = Auth::user()->yclients_id;
        }

        // Признак принудительного одновдения из yclients
        $isSync = $request->input("sync");

        list($table, $total) = TableReport::get($isSync, $start_date, $end_date, $company_id);

        $months = Utils::getMonthArray();

        $users = User::select("login", "name", "yclients_id")->orderBy("name")->get();

        $selected_month = $end_date;

        $selected_user = $company_id;

        if($table instanceof Collection) {
            $table = $table->sortBy([["income_total", "desc"]]);
        } else {
            usort($table, function($a, $b) {
                return $b["income_total"] <=> $a["income_total"];
            });
        }

        list("isDashboard" => $isDashboard) = RouteServiceProvider::getAdminTypeView();

        return view("analytics.show", compact(
            "table",
            "total",
            "months",
            "selected_month",
            "users",
            "selected_user",
            "isDashboard"
        ));
    }
}
