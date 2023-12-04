<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Services\YclientsService;
use Illuminate\Support\Str;
use Throwable;

/*
if(\Cache::has($companyStatsByStaff_id)) {
    $companyStatsByStaff = \Cache::get($companyStatsByStaff_id, false);
} else {
    $companyStatsByStaff = $client->getCompanyStatsByStaff($id);
    \Cache::put($companyStatsByStaff_id, $companyStatsByStaff, $cachingTime);
}
*/


class BranchReport extends Controller
{
    static function get($start_date, $end_date, $company_id) {
        try {
            $params = [
                "start_date" => $start_date,
                "end_date" => $end_date,
                "company_id" => $company_id
            ];

            $client = new YclientsService($params);

            // Список сотрудников
            $staff = $client->getStaff();

            // Коментарии
            $comments = $client->getCommentsByCompany();

            foreach ($staff as $one) {
                $id = $one["id"];

                // ID филиала
                $table[$id]["company_id"] = $company_id;

                // ID сотрудника
                $table[$id]["staff_id"] = $id;

                // Имя сотрудника
                $table[$id]["name"] = $one["name"];

                // Профессия
                $table[$id]["specialization"] = Str::limit($one["specialization"], 60, $end='...');

                // Средний чек, Заполняемость, Новые клиенты, Оборот,
                $companyStatsByStaff = $client->getCompanyStatsByStaff($id);

                if (is_array($companyStatsByStaff) && !empty($companyStatsByStaff)) {
                    $table[$id]["average_sum"] = round($companyStatsByStaff["average_sum"], 0);
                    $table[$id]["fullnesss"] = $companyStatsByStaff["fullnesss"];
                    $table[$id]["income_total"] = $companyStatsByStaff["income_total"];
                    $table[$id]["income_goods"] = $companyStatsByStaff["income_goods"];
                    $table[$id]["new_client"] = $companyStatsByStaff["new_client"];
                    $table[$id]["return_client"] = $companyStatsByStaff["return_client"];
                    $table[$id]["total_client"] = $companyStatsByStaff["new_client"] + $companyStatsByStaff["return_client"];
                } else {
                    $table[$id]["average_sum"] = 0;
                    $table[$id]["fullnesss"] = 0;
                    $table[$id]["income_total"] = 0;
                    $table[$id]["income_goods"] = 0;
                    $table[$id]["new_client"] = 0;
                    $table[$id]["return_client"] = 0;
                    $table[$id]["total_client"] = 0;
                }

                // Всего отзывов (из них 5)
                if (is_array($comments) && isset($comments[$id])) {
                    $table[$id]["comments_total"] = $comments[$id]["total"];
                    $table[$id]["comments_best"] = $comments[$id]["best"];
                } else {
                    $table[$id]["comments_total"] = 0;
                    $table[$id]["comments_best"] = 0;
                }

                // Лояльность и продажи
                $transactions = $client->getTransactionsCompanyByStaffId($id);

                if (is_array($transactions) && array_key_exists("loyalty", $transactions)) {
                    $table[$id]["loyalty"] = round($transactions["loyalty"], 0);
                } else {
                    $table[$id]["loyalty"] = 0;
                }

                if (is_array($transactions) && array_key_exists("sales", $transactions)) {
                    $table[$id]["sales"] = round($transactions["sales"], 0);
                } else {
                    $table[$id]["sales"] = 0;
                }

                // Дополнительные услуги
                $additional_services = $client->getRecordsByStaffId($id);

                if (is_numeric($additional_services)) {
                    $table[$id]["additional_services"] = round($additional_services, 0);
                } else {
                    $table[$id]["additional_services"] = 0;
                }

                // Сумма
                $table[$id]["sum"] = $table[$id]["additional_services"] + $table[$id]["sales"];
            }

            // Итого
            $stats = $client->getCompanyStats();

            $total = [
                "average_sum" => 0,
                "fullnesss" => 0,
                "new_client" => 0,
                "income_total" => 0,
                "loyalty" => 0,
                "additional_services" => 0,
                "sales" => 0,
                "income_goods" => 0,
                "comments_total" => 0,
                "comments_best" => 0,
            ];

            if (is_array($stats) && !empty($stats)) {
                $total["average_sum"] = round($stats["average_sum"], 0);
                $total["fullnesss"] = $stats["fullnesss"];
                $total["new_client"] = $stats["new_client"];
                $total["income_total"] = $stats["income_total"];
                $total["income_goods"] = $stats["income_goods"];
            }

            foreach ($table as $one) {
                $total["loyalty"] += $one["loyalty"];
                $total["additional_services"] += $one["additional_services"];
                $total["sales"] += $one["sales"];
                $total["comments_total"] += $one["comments_total"];
                $total["comments_best"] += $one["comments_best"];
            }

            return [$table, $total];

        } catch (Throwable $e) {
            report($e);

            return [[],[]];
        }
    }
}
