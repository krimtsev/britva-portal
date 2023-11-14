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

class ShowController extends Controller
{
    public function __invoke(Request $request)
    {
        // Дата окончания (передается из формы)
        $end_date = $request->input('month');

        // Дата начала
        $arr_end_date = explode("-", $end_date);
        $arr_end_date[2] = "01";
        $start_date = implode("-", $arr_end_date);

        // ID филиала
        $company_id = $request->input('company_id');

        // Признак принудительного одновдения из yclients
        $isSync = $request->input('sync');

        $table = YclientsBranchReport::where('company_id', $company_id)
            ->where('start_date', $start_date)
            ->where('end_date', $end_date)
            ->get();

        $total = YclientsBranchTotalReport::where('company_id', $company_id)
            ->where('start_date', $start_date)
            ->where('end_date', $end_date)
            ->first();

        if ($isSync || (count($table) == 0 || !$total)) {

            list($table, $total) = BranchReport::get($start_date, $end_date, $company_id);

            if (!empty($table)) {
                foreach ($table as $one) {
                    $tmp_table =   [
                        "company_id"     => $company_id,
                        "staff_id"       => $one["id"],
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
                        "start_date"     => $start_date,
                        "end_date"       => $end_date,
                    ];

                    YclientsBranchReport::addRecord($tmp_table);
                }
            }

            if (!empty($total)) {
                $total["company_id"] = $company_id;
                $total["start_date"] = $start_date;
                $total["end_date"] = $end_date;

                YclientsBranchTotalReport::addRecord($total);
            }
        }

        $months = Utils::getMonthArray();

        $users = User::select('login', 'name', 'yclients_id')->orderBy('name')->get();

        $selected_month = $end_date;

        $selected_user = $company_id;

        if($table instanceof Collection) {
            $table = $table->sortBy([['income_total', 'desc']]);
        } else {
            usort($table, function($a, $b) {
                return $b['income_total'] <=> $a['income_total'];
            });
        }

        return view('analytics.show', compact(
            'table',
            'total',
            'months',
            'selected_month',
            'users',
            'selected_user'
        ));
    }
}
