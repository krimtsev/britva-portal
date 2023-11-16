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

class ChartCompanyController extends Controller
{
    public function __invoke(Request $request)
    {
        // Дата окончания (передается из формы)
        $end_date = $request->input('month');

        // Дата начала
        $start_date =  Utils::setFirstDay($end_date);

        // ID филиала
        $company_id = $request->input('company_id');

        // Признак принудительного одновдения из yclients
        $isSync = $request->input('sync');

        $dates = Utils::getPeriodMonthArray($start_date, $end_date, 3);

        $total_list = [];

        foreach ($dates as $date) {
            $table = YclientsBranchReport::where('company_id', $company_id)
                ->where('start_date', $date["start_date"])
                ->where('end_date', $date["end_date"])
                ->get();

            $total = YclientsBranchTotalReport::where('company_id', $company_id)
                ->where('start_date', $date["start_date"])
                ->where('end_date', $date["end_date"])
                ->first();

            if ($isSync || (count($table) == 0 || !$total)) {

                list($table, $total) = BranchReport::get($date["start_date"], $date["end_date"], $company_id);

                if (!empty($table)) {
                    foreach ($table as $one) {
                        $tmp_table = [
                            "company_id" => $company_id,
                            "staff_id" => $one["id"],
                            "name" => $one["name"],
                            "specialization" => $one["specialization"],
                            "average_sum" => $one["average_sum"],
                            "fullnesss" => $one["fullnesss"],
                            "new_client" => $one["new_client"],
                            "income_total" => $one["income_total"],
                            "income_goods" => $one["income_goods"],
                            "comments_total" => $one["comments_total"],
                            "comments_best" => $one["comments_best"],
                            "loyalty" => $one["loyalty"],
                            "sales" => $one["sales"],
                            "additional_services" => $one["additional_services"],
                            "sum" => $one["sum"],
                            "start_date" => $date["start_date"],
                            "end_date" => $date["end_date"],
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

            $total_list[] = $total;
        }

        $months = Utils::getMonthArray();

        $users = User::select('login', 'name', 'yclients_id')->orderBy('name')->get();

        $selected_month = $end_date;

        $selected_user = $company_id;

        $total_list = json_encode($total_list);

        return view('analytics.company', compact(
            'total_list',
            'months',
            'selected_month',
            'users',
            'selected_user'
        ));
    }
}
