<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Services\YclientsService;
use Illuminate\Http\Request;
use Utils;

/**
 *  1. getStaff - получаем список сотрудников
 *  2. getCompanyStatsByStaff - по кождому сотруднику получаем Средний чек, Заполняемость, Новые клиенты
 *  3.
 */

class ShowController extends Controller
{
    public function __invoke(Request $request)
    {

        $months = Utils::getMonthArray();

        $data = request()->validate([
            'month' => 'required|string',
        ]);

        $start_date = explode("_", $data["month"]);
        $start_date[2] = "01";
        $start_date = implode('-', $start_date);

        $end_date = $data["month"];

        \Cache::clear();

        $company_id = 747327;

        $params = [
            "start_date" => $start_date,
            "end_date"   => $end_date,
            "company_id" => $company_id
        ];

        $client = new YclientsService($params);

        $cachingTime = now()->addMinutes(10);

        // staff

        if(\Cache::has("staff")) {
            $staff = \Cache::get("staff", []);
        } else {
            $staff = $client->getStaff();
            \Cache::put("staff", $staff, $cachingTime);
        }

        // comments

        if(\Cache::has("comments")) {
            $comments = \Cache::get("comments", []);
        } else {
            $comments = $client->getCommentsByCompany();
            \Cache::put('comments', $comments, $cachingTime);
        }

        foreach ($staff as $one) {
            $id = $one["id"];

            $table[$id]["id"] = $id;
            $table[$id]["name"] = explode(" ", $one["name"])[0];
            $table[$id]["specialization"] = $one["specialization"];

            // Средний чек, Заполняемость, Новые клиенты, Оборот

            $companyStatsByStaff_id = "companyStatsByStaff_".$id.$company_id;

            if(\Cache::has($companyStatsByStaff_id)) {
                $companyStatsByStaff = \Cache::get($companyStatsByStaff_id, false);
            } else {
                $companyStatsByStaff = $client->getCompanyStatsByStaff($id);
                \Cache::put($companyStatsByStaff_id, $companyStatsByStaff, $cachingTime);
            }

            if (is_array($companyStatsByStaff) && !empty($companyStatsByStaff)) {
                $table[$id]["average_sum"] = round($companyStatsByStaff["average_sum"]);
                $table[$id]["fullnesss"] = round($companyStatsByStaff["fullnesss"]);
                $table[$id]["new_client"] = round($companyStatsByStaff["new_client"]);
                $table[$id]["income_total"] = round($companyStatsByStaff["income_total"]);
            } else {
                $table[$id]["average_sum"] = 0;
                $table[$id]["fullnesss"] = 0;
                $table[$id]["new_client"] = 0;
                $table[$id]["income_total"] = 0;
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

            $transactions_id = "transactions_".$id.$company_id;

            if(\Cache::has($transactions_id)) {
                $transactions = \Cache::get($transactions_id, 0);
            } else {
                $transactions = $client->getTransactionsCompanyByStaffId($id);
                \Cache::put($transactions_id, $transactions, $cachingTime);
            }

            if (is_array($transactions) && array_key_exists("loyalty", $transactions)) {
                $table[$id]["loyalty"] = round($transactions["loyalty"]);
            } else {
                $table[$id]["loyalty"] = 0;
            }

            if (is_array($transactions) && array_key_exists("sales", $transactions)) {
                $table[$id]["sales"] = round($transactions["sales"]);
            } else {
                $table[$id]["sales"] = 0;
            }

            // Доп услуги
            $add_services_id = "add_services_".$id.$company_id;

            if(\Cache::has($add_services_id)) {
                $add_services = \Cache::get($add_services_id, 0);
            } else {
                $add_services = $client->getRecordsByStaffId($id);
                \Cache::put($add_services_id, $add_services, $cachingTime);
            }

            if (is_numeric($add_services)) {
                $table[$id]["add_services"] = round($add_services);
            } else {
                $table[$id]["add_services"] = 0;
            }

            // Сумма

            $table[$id]["sum"] = $table[$id]["add_services"] + $table[$id]["sales"];
        }

        uasort($table, function ($item1, $item2) { return $item2["income_total"] <=> $item1["income_total"]; } );

        // Итого

        $total = [
            "average_sum" => 0,
            "fullnesss" => 0,
            "new_client" => 0,
            "income_total" => 0,
            "loyalty" => 0,
            "add_services" => 0,
            "sales" => 0,
            "comments_total" => 0,
            "comments_best" => 0,
        ];
        $stats = $client->getCompanyStats();

        if (is_array($stats) && !empty($stats)) {
            $total["average_sum"] = $stats["average_sum"];
            $total["fullnesss"] = $stats["fullnesss"];
            $total["new_client"] = $stats["new_client"];
            $total["income_total"] = $stats["income_total"];
        }

        foreach ($table as $one) {
            $total["loyalty"] += $one["loyalty"];
            $total["add_services"] += $one["add_services"];
            $total["sales"] += $one["sales"];
            $total["comments_total"] += $one["comments_total"];
            $total["comments_best"] += $one["comments_best"];
        }

        return view('profile.analitics.show', compact('table', 'total', 'params', 'months'));
    }

}

/*
 *  "id" => 2717722
    "name" => "Георгий"
    "company_id" => 41120
    "specialization" => "БАРБЕР СЕТИ BRITVA"
    "information" => ""
    "api_id" => null
    "fired" => 0
    "is_fired" => false
    "dismissal_date" => null
    "dismissal_reason" => null
    "hidden" => 0
    "is_online" => true
    "status" => 0
    "is_deleted" => false
    "user_id" => 12452348
    "rating" => 4.93
    "prepaid" => "forbidden"
    "is_chain" => false
    "weight" => 157
    "is_rating_shown" => true
    "is_online_random_choice_allowed" => true
    "seance_step" => 0
    "seance_search_step" => 900
    "seance_search_start" => 0
    "seance_search_finish" => 86400
    "avatar" => "https://assets.yclients.com/masters/sm/d/df/df6c4949367f1de_20230816134408.png"
    "avatar_big" => "https://assets.yclients.com/masters/origin/8/88/884872ec0ad10fe_20230816134409.png"
    "position" => null
    "user" => array:3 [▶]
    "is_bookable" => true
    "services_links" => array:20 [▶]
    "schedule_till" => "2023-10-01"
    "employee" => null
    "chain" => null
    "grid_settings" => array:2 [▼
      "weekdays_settings" => array:7 [▶]
      "dates_settings" => []
    ]
 */
