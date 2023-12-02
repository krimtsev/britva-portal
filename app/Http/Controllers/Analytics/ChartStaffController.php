<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Analytics\TableReport;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

class ChartStaffController extends Controller
{
    public function __invoke(Request $request)
    {
        // Дата окончания (передается из формы)
        $selected_month = $request->input("month");

        // ID филиала
        $selected_user = $request->input("company_id");

        // ID сотрудника
        $staff_id = $request->input("staff_id");

        // Признак принудительного одновдения из yclients
        $isSync = $request->input("sync");

        $dates = Utils::getPeriodMonthArray($selected_month, 4);

        $table_list = [];

        foreach ($dates as $date) {
            list($table, $total) = TableReport::get($isSync, $date["start_date"], $date["end_date"], $selected_user);

            if($table instanceof Collection) {
                $table_list[] = array_merge(...$table->where("staff_id", $staff_id)->toArray());

            } else {
                $staffData = Arr::first($table, function($staff) use ($staff_id) {
                    return $staff["staff_id"] == $staff_id;
                });
                if(!empty($staffData)) {
                    $table_list[] = $staffData;
                }
            }
        }

        $months = Utils::getMonthArray();

        $users = User::select("login", "name", "yclients_id")->orderBy("name")->get();

        $selected_period = json_encode(array_map(function($date) {
            return Utils::dateToMothAndYear($date["start_date"]);
        }, array_reverse($dates)));

        $table_last_data = [];
        $total = [];

        if (count($table_list) >= 1){
            $table_list = array_reverse($table_list);
            $table_last_data = $table_list[array_key_last($table_list)];
        }
        $table_list = json_encode($table_list);

        if (!empty($table_last_data)) {
            $total = [
                "name"                => $table_last_data["name"],
                "specialization"      => $table_last_data["specialization"],
                "fullnesss"           => $table_last_data["fullnesss"],
                "new_client"          => $table_last_data["new_client"],
                "total_client"        => $table_last_data["total_client"],
                "return_client"       => $table_last_data["return_client"],
                "month"               => $months[$selected_month],
                "additional_services" => Utils::toNumberFormat($table_last_data["additional_services"]),
                "average_sum"         => Utils::toNumberFormat($table_last_data["average_sum"]),
                "sales"               => Utils::toNumberFormat($table_last_data["sales"]),
                "income_total"        => Utils::toNumberFormat($table_last_data["income_total"]),
            ];
        }

        list("isDashboard" => $isDashboard) = RouteServiceProvider::getAdminTypeView();

        return view("analytics.staff", compact(
            "table_list",
            "total",
            "months",
            "selected_month",
            "users",
            "selected_user",
            "selected_period",
            "staff_id",
            "isDashboard"
        ));
    }
}
