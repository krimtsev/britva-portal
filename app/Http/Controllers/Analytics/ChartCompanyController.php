<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Analytics\TableReport;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Utils\Utils;
use Illuminate\Http\Request;

class ChartCompanyController extends Controller
{
    public function __invoke(Request $request)
    {
        // Дата окончания (передается из формы)
        $end_date = $request->input("month");

        // Дата начала
        $start_date =  Utils::setFirstDay($end_date);

        // ID филиала
        $company_id = $request->input("company_id");

        // Признак принудительного одновдения из yclients
        $isSync = $request->input("sync");

        $dates = Utils::getPeriodMonthArray($start_date, $end_date, 3);

        $total_list = [];

        foreach ($dates as $date) {
            list($table, $total) = TableReport::get($isSync, $date["start_date"], $date["end_date"], $company_id);

            $total_list[] = $total;
        }

        $months = Utils::getMonthArray();

        $users = User::select("login", "name", "yclients_id")->orderBy("name")->get();

        $selected_month = $end_date;

        $selected_user = $company_id;

        $total_list = json_encode(array_reverse($total_list));

        $selected_period = json_encode(array_map(function($date) {
            return Utils::dateToMothAndYear($date["start_date"]);
        }, array_reverse($dates)));

        list("isDashboard" => $isDashboard) = RouteServiceProvider::getAdminTypeView();

        return view("analytics.company", compact(
            "total_list",
            "months",
            "selected_month",
            "users",
            "selected_user",
            "selected_period",
            "isDashboard"
        ));
    }
}