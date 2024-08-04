<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Services\YclientsService;
use Illuminate\Support\Str;
use Throwable;

class BranchReportStaff extends Controller
{
    static function get($client, $staff, $company_id, $comments) {
        try {
            $id = $staff["id"];

            $data = [
                "staff_id"       => $id,
                "company_id"     => $company_id,
                "name"           => $staff["name"],
                "specialization" => Str::limit($staff["specialization"], 60, $end='...'),
            ];

            // Средний чек, Заполняемость, Новые клиенты, Оборот,
            $companyStatsByStaff = $client->getCompanyStatsByStaff($id);

            if (is_array($companyStatsByStaff) && !empty($companyStatsByStaff)) {
                $data["average_sum"] = round($companyStatsByStaff["average_sum"], 0);
                $data["fullness"] = $companyStatsByStaff["fullness"];
                $data["income_total"] = $companyStatsByStaff["income_total"];
                $data["income_goods"] = $companyStatsByStaff["income_goods"];
                $data["new_client"] = $companyStatsByStaff["new_client"];
                $data["return_client"] = $companyStatsByStaff["return_client"];
                $data["total_client"] = $companyStatsByStaff["new_client"] + $companyStatsByStaff["return_client"];
            } else {
                $data["average_sum"] = 0;
                $data["fullness"] = 0;
                $data["income_total"] = 0;
                $data["income_goods"] = 0;
                $data["new_client"] = 0;
                $data["return_client"] = 0;
                $data["total_client"] = 0;
            }

            // Всего отзывов (из них 5)
            if (is_array($comments) && isset($comments[$id])) {
                $data["comments_total"] = $comments[$id]["total"];
                $data["comments_best"] = $comments[$id]["best"];
            } else {
                $data["comments_total"] = 0;
                $data["comments_best"] = 0;
            }

            // Лояльность и продажи
            $transactions = $client->getTransactionsCompanyByStaffId($id);

            if (is_array($transactions) && array_key_exists("loyalty", $transactions)) {
                $data["loyalty"] = round($transactions["loyalty"], 0);
            } else {
                $data["loyalty"] = 0;
            }

            if (is_array($transactions) && array_key_exists("sales", $transactions)) {
                $data["sales"] = round($transactions["sales"], 0);
            } else {
                $data["sales"] = 0;
            }

            // Дополнительные услуги
            $additional_services = $client->getRecordsByStaffId($id);

            if (is_numeric($additional_services)) {
                $data["additional_services"] = round($additional_services, 0);
            } else {
                $data["additional_services"] = 0;
            }

            // Сумма
            $data["sum"] = $data["additional_services"] + $data["sales"];

            return $data;

        } catch (Throwable $e) {
            report($e->getMessage());

            return [];
        }
    }
}
