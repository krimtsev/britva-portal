<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\User;
use App\Models\YclientsBranchTotalReport;
use App\Providers\RouteServiceProvider;
use App\Utils\Utils;
use Illuminate\Support\Arr;

class StatusCompanyController extends Controller
{
    public function __invoke()
    {
        $selected_month = Utils::getPreviousStartMonth();

        $dates =  array_map(function (array $value) {
            return $value["start_date"];
        }, Utils::getPeriodMonthArray($selected_month, 6));

        $partners = Partner::select("name", "yclients_id")
            ->where('yclients_id', '<>', "")
            ->orderBy("name")
            ->get();

        $total_list = YclientsBranchTotalReport::select("company_id", "start_date")->whereIn("start_date", $dates)->get();
        $total_list = $total_list->groupBy("company_id");

        $table = [];

        foreach ($partners as $partner) {
            $table[] = [
                "name"        => $partner->name,
                "dates"       => self::checkedDatesList($dates, $total_list->get($partner->yclients_id))
            ];
        }

        list("isDashboard" => $isDashboard) = RouteServiceProvider::getAdminTypeView();

        $months = Utils::getMonthArray(Arr::last($dates), Arr::first($dates));

        return view("dashboard.jobs.status-company", compact(
            "table",
            "dates",
            "months",
            "isDashboard"
        ));
    }

    private function checkedDatesList($dates, $total) {
        $_dates = [];
        foreach ($dates as $date) {
            $_dates[] = !empty($total) && $total->contains("start_date", $date) ? true : false;
        }
        return $_dates;
    }
}
