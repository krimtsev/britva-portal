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
    private $client;
    private $company_id;
    public function __construct($start_date, $end_date, $company_id)
    {
        $params = [
            "start_date" => $start_date,
            "end_date" => $end_date,
            "company_id" => $company_id
        ];

        $this->company_id = $company_id;

        $this->client = new YclientsService($params);
    }

    public function get(): array
    {
        try {
            // Список сотрудников
            $staff = $this->client->getStaff();

            // Коментарии
            $comments = $this->client->getCommentsByCompany();

            $table = [];

            foreach ($staff as $one) {
                $id = $one["id"];
                $table[$id] = self::reportStaff($one, $comments);
            }

            // Итого
            $stats = $this->client->getCompanyStats();

            $total = [
                "average_sum" => 0,
                "fullness" => 0,
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
                $total["fullness"] = $stats["fullness"];
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
            report($e->getMessage());

            return [[],[]];
        }
    }

    public function reportStaff($staff, $comments): array
    {
        try {
            $id = $staff["id"];

            $data = [
                "staff_id"       => $id,
                "company_id"     => $this->company_id,
                "name"           => $staff["name"],
                "specialization" => Str::limit($staff["specialization"], 60, $end='...'),
            ];

            // Средний чек, Заполняемость, Новые клиенты, Оборот,
            $companyStatsByStaff = $this->client->getCompanyStatsByStaff($id);

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
            $transactions = $this->client->getTransactionsCompanyByStaffId($id);

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
            $additional_services = $this->client->getRecordsByStaffId($id);

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
