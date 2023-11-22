<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Analytics\BranchReport;
use App\Models\User;
use App\Models\YclientsBranchReport;
use App\Models\YclientsBranchTotalReport;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 *  1. getStaff - получаем список сотрудников
 *  2. getCompanyStatsByStaff - по кождому сотруднику получаем Средний чек, Заполняемость, Новые клиенты
 *  3.
 */

class ChartStaffController extends Controller
{
    public function __invoke(Request $request)
    {
        // Дата окончания (передается из формы)
        $end_date = $request->input("month");

        // Дата начала
        $start_date =  Utils::setFirstDay($end_date);

        // ID филиала
        $company_id = $request->input("company_id");

        // ID сотрудника
        $staff_id = $request->input("staff_id");

        // Признак принудительного одновдения из yclients
        $isSync = $request->input("sync");

        $dates = Utils::getPeriodMonthArray($start_date, $end_date, 3);

        $table_list = [];

        foreach ($dates as $date) {
            $table = YclientsBranchReport::where("company_id", $company_id)
                ->where("start_date", $date["start_date"])
                ->where("end_date", $date["end_date"])
                ->get();

            $total = YclientsBranchTotalReport::where("company_id", $company_id)
                ->where("start_date", $date["start_date"])
                ->where("end_date", $date["end_date"])
                ->first();

            if ($isSync || (count($table) == 0 || !$total)) {

                list($table, $total) = BranchReport::get($date["start_date"], $date["end_date"], $company_id);

                if (!empty($table)) {
                    foreach ($table as $one) {
                        $tmp_table = [
                            "company_id"     => $one["company_id"],
                            "staff_id"       => $one["staff_id"],
                            "name"           => $one["name"],
                            "specialization" => $one["specialization"],
                            "average_sum"    => $one["average_sum"],
                            "fullnesss"      => $one["fullnesss"],
                            "new_client"     => $one["new_client"],
                            "income_total"   => $one["income_total"],
                            "income_goods"   => $one["income_goods"],
                            "comments_total" => $one["comments_total"],
                            "comments_best"  => $one["comments_best"],
                            "loyalty"        => $one["loyalty"],
                            "sales"          => $one["sales"],
                            "additional_services" => $one["additional_services"],
                            "sum"            => $one["sum"],
                            "total_client"   => $one["total_client"],
                            "return_client"  => $one["return_client"],
                            "start_date"     => $date["start_date"],
                            "end_date"       => $date["end_date"],
                        ];

                        YclientsBranchReport::addRecord($tmp_table);
                    }
                }

                if (!empty($total)) {
                    $total["company_id"] = $company_id;
                    $total["start_date"] = $date["start_date"];
                    $total["end_date"] = $date["end_date"];

                    YclientsBranchTotalReport::addRecord($total);
                }
            }

            if($table instanceof Collection) {
                $table_list[] = array_merge(...$table->where("staff_id", $staff_id)->toArray());
            } else {
                $result = array_filter($table, function($staff) use ($staff_id) {
                    return $staff["staff_id"] == $staff_id;
                });
                if(!empty($result[$staff_id])) {
                    $table_list[] = $result[$staff_id];
                }
            }
        }

        $months = Utils::getMonthArray();

        $users = User::select("login", "name", "yclients_id")->orderBy("name")->get();

        $selected_month = $end_date;

        $selected_user = $company_id;

        $selected_period = json_encode(array_map(function($date) {
            return Utils::dateToMothAndYear($date["start_date"]);
        }, array_reverse($dates)));

        $table_list = array_reverse($table_list);
        $table_last_data = $table_list[array_key_last($table_list)];
        $table_list = json_encode($table_list);

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

        return view("analytics.staff", compact(
            "table_list",
            "total",
            "months",
            "selected_month",
            "users",
            "selected_user",
            "selected_period",
            "staff_id"
        ));
    }
}
