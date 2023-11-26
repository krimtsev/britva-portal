<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Analytics\BranchReport;
use App\Http\Controllers\Controller;
use App\Models\YclientsBranchReport;
use App\Models\YclientsBranchTotalReport;
use Illuminate\Support\Collection;

class TableReport extends Controller
{
    public static function get($isSync, $start_date, $end_date, $company_id)
    {
        $table = YclientsBranchReport::where("company_id", $company_id)
            ->where("start_date", $start_date)
            ->where("end_date", $end_date)
            ->get();

        $total = YclientsBranchTotalReport::where("company_id", $company_id)
            ->where("start_date", $start_date)
            ->where("end_date", $end_date)
            ->first();

        if ($isSync || (count($table) == 0 || !$total)) {

            list($table, $total) = BranchReport::get($start_date, $end_date, $company_id);

            if (!empty($table)) {
                foreach ($table as $one) {
                    $tmp_table =   [
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

        if($table instanceof Collection) {
            $table = $table->sortBy([["income_total", "desc"]]);
        } else {
            usort($table, function($a, $b) {
                return $b["income_total"] <=> $a["income_total"];
            });
        }

        return [$table, $total];
    }
}
