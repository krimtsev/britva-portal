<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class YclientsService
{
    /** Динасический ID филиала */
    private $company_id;

    /** Фиксированный токен приложения */
    private $app_token;

    /** Фиксированный токен разработчика */
    private $partner_token;

    /** Максимальное кол-во записей для запроса */
    private $count = 1000;

    /** Дата начала выборки */
    private $start_date;

    /** Дата окончания выборки */
    private $end_date;

    public function __construct($params)
    {
        $this->app_token = env('YCLIENTS_APP_TOKEN', '');
        $this->partner_token = env('YCLIENTS_PARTNER_TOKEN', '');
        $this->company_id = $params["company_id"];

        $this->start_date = $params["start_date"];
        $this->end_date = $params["end_date"];
    }

    private function httpWithHeaders() {
        return Http::withHeaders([
            "Accept"        => "application/vnd.yclients.v2+json",
            "Content-Type"  => "application/json",
            "Authorization" => sprintf("Bearer %s, User %s", $this->partner_token, $this->app_token),
        ])->withOptions([
            "verify" => false,
        ]);
    }

    /**
     * Фильтр уволенных сотрудников, удаленных сотрудников, лист ожидания. 2191383
     */
    private function isRemoveStaff($data) {
        return $data["is_fired"] || $data["is_deleted"] ; // || strtoupper($data["name"]) == "ЛИСТ ОЖИДАНИЯ"
    }

    /**
     * Получение списка сотрудников
     * @return false | array<string[]>
     */
	 
    public function getStaff() {
        try {
			
			//dd($this->company_id);
			
            $url = sprintf("https://api.yclients.com/api/v1/company/%s/staff", $this->company_id);
			
            $response = $this->httpWithHeaders()->get($url);

            $response = $response->json($key = null);
			
            if(!$response["success"]) {
                return false;
            }

            $staff = [];

            foreach ($response["data"] as $one) {
                if($this->isRemoveStaff($one)) {
                    continue;
                }
                $id = $one["id"];

                $staff[$id]["id"] = $one["id"];
                $staff[$id]["name"] = $one["name"];
                $staff[$id]["specialization"] = $one["specialization"];
            }

            return $staff;

        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }

    /**
     * Получить основные показатели компании по сотруднику ?
     * @return false | array<int, string, string>
     */
    public function getCompanyStatsByStaff($staff_id) {
        try {
            $query = http_build_query([
                "date_from" => $this->start_date,
                "date_to"   => $this->end_date,
                "staff_id"  => $staff_id
            ]);
            $url = sprintf("https://api.yclients.com/api/v1/company/%s/analytics/overall/?%s", $this->company_id, $query);

            $response = $this->httpWithHeaders()->get($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            return [
                "average_sum" => (int) $response["data"]["income_average_stats"]["current_sum"],
                "fullnesss" => $response["data"]["fullness_stats"]["current_percent"],
                "new_client" => $response["data"]["client_stats"]["new_count"],
                "income_total" => $response["data"]["income_total_stats"]["current_sum"],
                "income_goods" => $response["data"]["income_goods_stats"]["current_sum"],
            ];

        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }


    /**
     * Получить основные показатели компании за период
     * @return false | array<int, string, string>
     */
    public function getCompanyStats() {
        try {
            $query = http_build_query([
                "date_from" => $this->start_date,
                "date_to"   => $this->end_date,
            ]);

            $url = sprintf("https://api.yclients.com/api/v1/company/%s/analytics/overall/?%s", $this->company_id, $query);

            $response = $this->httpWithHeaders()->get($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            return [
                "average_sum" => (int) $response["data"]["income_average_stats"]["current_sum"],
                "fullnesss" => $response["data"]["fullness_stats"]["current_percent"],
                "new_client" => $response["data"]["client_stats"]["new_count"],
                "income_total" => $response["data"]["income_total_stats"]["current_sum"],
                "income_goods" => $response["data"]["income_goods_stats"]["current_sum"],
            ];

        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }

    /**
     * Получения отзывов на сотрудников по филиалу
     * @return false | array<int, int>
     */
    function getCommentsByCompany() {
        try {
            $MAX_RATING = 5;

            $query = http_build_query([
                "start_date" => $this->start_date,
                "end_date"   => $this->end_date,
                "count"      => 2000,
                "page"       => 1,
                // "staff_id"  => $staff_id - можно фильтровать по сотруднику
            ]);
            $url = sprintf("https://api.yclients.com/api/v1/comments/%s?%s", $this->company_id, $query);

            $response = $this->httpWithHeaders()->get($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            $comments = [];

            foreach ($response["data"] as $one) {
                $key = $one["master_id"];

                if (!array_key_exists($key, $comments)) {
                    $comments[$key]["total"] = 0;
                    $comments[$key]["best"] = 0;
                }

                $comments[$key]["total"] += 1;

                if ($one["rating"] === $MAX_RATING) {
                    $comments[$key]["best"] += 1;
                }
            }

            return $comments;

        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }

    /**
     * Получение проданных абонементов и сертификатов по компании
     * @return false | array<int, int>
     */
    function getTransactionsCompanyByStaffId($staff_id) {
        try {
            $FILTER_LOYALTY_IDS = [
                6, // Продажа абонементов
                12 // Продажа сертификатов
            ];

            $FILTER_SALES_IDS = [
                7, // Продажа товаров
            ];

            $query = http_build_query([
                "start_date" => $this->start_date,
                "end_date"   => $this->end_date,
                "master_id"  => $staff_id,
                "count"      => 10000,
                "page"       => 1,
            ]);
            $url = sprintf("https://api.yclients.com/api/v1/transactions/%s?%s", $this->company_id, $query);

            $response = $this->httpWithHeaders()->get($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            $loyalty = 0;
            $sales = 0;

            foreach ($response["data"] as $one) {
                if (in_array($one["expense"]["id"], $FILTER_LOYALTY_IDS)) {
                    $loyalty += $one["amount"];
                }

                if (in_array($one["expense"]["id"], $FILTER_SALES_IDS)) {
                    $sales += $one["amount"];
                }
            }

            return [
                "loyalty" => $loyalty,
                "sales" => $sales
            ];
        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }


    /**
     * Получить список записей по сотруднику
     * @return false | int
     */
    function getRecordsByStaffId($staff_id) {
        try {
            $query = http_build_query([
                "start_date" => $this->start_date,
                "end_date"   => $this->end_date,
                "count"      => $this->count,
                "staff_id"   => $staff_id,
            ]);

            // Услуги

            $FILTER_IDS = [
                5778125,  // ACUMEN
                1846923,  // BLACK MASK
                9771809,  // DEPOT
                7188703,  // MGC QUICK SPA
                6536565,  // VOLCANO
                12724278, // ВОССТАНАВЛИВАЮЩАЯ ТЕРАПИЯ ДЛЯ ЛИЦА RHEA
                13621893, // ВОССТАНАВЛИВАЮЩАЯ ТЕРАПИЯ ДЛЯ ЛИЦА RHEA + ПАТЧИ
                8220685,  // КОМПЛЕКС ПО УХОДУ ЗА КОЖЕЙ ОТ LONDON GROOMING
                10052100, // КОМПЛЕКС ПО УХОДУ ЗА КОЖЕЙ ОТ LONDON GROOMING (СО СКРАБОМ)
                10823857, // ЛИМФОДРИНАЖНЫЙ МАССАЖ
                10802729, // МАССАЖ ЛИЦА
                10802743, // МАССАЖ ЛИЦА + ПИЛИНГ + УЛЬТРАЗВУКОВАЯ ЧИСТКА + АЛЬГИНАТНАЯ МАСКА + УВЛАЖНЯЮЩИЙ КРЕМ
                10802739, // ПИЛИНГ + УЛЬТРАЗВУКОВАЯ ЧИСТКА + АЛЬГИНАТНАЯ МАСКА + УВЛАЖНЯЮЩИЙ КРЕМ
                10823866, // РАССЛАБЛЯЮЩИЙ МАССАЖ
                10802726, // УВЛАЖНЯЮЩАЯ МАСКА
                10802733, // УЛЬТРАЗВУКОВАЯ ЧИСТКА + АЛЬГИНАТНАЯ МАСКА + УВЛАЖНЯЮЩИЙ КРЕМ
                13621869, // УХОД ВОКРУГ ГЛАЗ RHEA

                10659272, // ART HAIR TATTOO
                1928689,  // HAIR TATTOO
                1510491,  // ВОСК // КОМПЛЕКС
                1510490,  // ВОСК // УШИ + НОС
                5778126,  // ВОСК // БРОВИ
                1510488,  // ВОСК // ЩЕКИ
                8424387,  // ДЕТОКС КОЖИ ГОЛОВЫ GRAHAM HILL
                12034752, // КОМПЛЕКСНЫЙ УХОД ЗА КОЖЕЙ ГОЛОВЫ И ВОЛОСАМИ ОТ SOLOMON’S
                12320049, // КОМПЛЕКСНЫЙ УХОД ЗА КОЖЕЙ ГОЛОВЫ И ВОЛОСАМИ ОТ SYSTEM 4
                12863952, // КОМПЛЕКСНЫЙ УХОД ЗА КОЖЕЙ ГОЛОВЫ И ВОЛОСАМИ ОТ THEO
                13695706, //КОРРЕКЦИЯ + ОКРАШИВАНИЕ БРОВЕЙ
                13695709, //КОРРЕКЦИЯ БРОВЕЙ
                2695459,  // ОКАНТОВКА ГОЛОВЫ
                12902309, // ОКРАШИВАНИЕ БРОВЕЙ
                5769238,  // ПАТЧИ
                6540050,  // ТОНИРОВАНИЕ БОРОДЫ
                6540052,  // ТОНИРОВАНИЕ ГОЛОВЫ
                1510473,  // УКЛАДКА БЕЗ СТРИЖКИ
            ];

            // Услуги в комплексе

            $SERVICES_COST = [
                5855572 => 1600, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК - 1600 рублей
                5855566 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК - 400 рулей
                13458944 => 2800, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT - 2800 рублей
                13458949 => 3200, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК - 3200 рублей
                5855583 => 1600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК - 1600 рублей
                5855560 => 1200, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN - 1200 рублей

                7043251 => 1200, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN // ТОП-БАРБЕР - 1200 рублей
                7043266 => 1600, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК // ТОП-БАРБЕР - 1600 рублей
                7043330 => 1600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК // ТОП-БАРБЕР - 1600 рублей
                13458978 => 3200, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК // ТОП-БАРБЕР - 3200 рублей
                13458971 => 2800, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT // ТОП-БАРБЕР - 2800 рублей
                7043262 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // ТОП-БАРБЕР - 400 рублей

                8337664 => 1200, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN // БРЕНД-БАРБЕР - 1200 рублей
                8337665 => 1600, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК // БРЕНД-БАРБЕР - 1600 рублей
                8337669 => 1600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК // БРЕНД-БАРБЕР - 1600 рублей
                13458996 => 3200, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК // БРЕНД-БАРБЕР - 3200 рублей
                13458994 => 2800, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT // БРЕНД-БАРБЕР - 2800 рублей
                8337666 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // БРЕНД-БАРБЕР - 400 рублей

                12320307 => 2800, // МУЖСКАЯ СТРИЖКА + DEPOT // ЭКСПЕРТ - 2800 рублей
                12320304 => 3200, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК // ЭКСПЕРТ - 3200 рублей
                12320306 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // ЭКСПЕРТ - 400 рублей
            ];

            $SERVICES_COST_IDS = array_keys($SERVICES_COST);

            $url = sprintf("https://api.yclients.com/api/v1/records/%s?%s", $this->company_id, $query);

            $response = $this->httpWithHeaders()->get($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            $total = 0;

            foreach ($response["data"] as $one) {
                if (!is_array($one["services"]) && count($one["services"]) === 0)
                    continue;

                foreach ($one["services"] as $service) {
                    if (in_array($service["id"], $FILTER_IDS)) {
                        $total += $service["cost_to_pay"];
                    }

                    if (in_array($service["id"], $SERVICES_COST_IDS)) {
                        $total += $SERVICES_COST[$service["id"]];
                    }
                }
            }

            return $total;

        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }


    /**
     * Получить список услуг в категории
     * @return false | array
     */
    function getServicesById($category_id) {
        $query = http_build_query([
            "category_id"   => $category_id,
        ]);

        $url = sprintf("https://api.yclients.com/api/v1/company/%s/services", $this->company_id, $query);

        $response = $this->httpWithHeaders()->get($url);

        $response = $response->json($key = null);

        if(!$response["success"]) {
            return false;
        }

        return $response["data"];
    }
}


/*
    "income_total_stats" => array:4 [▼
        "current_sum" => "3_155_801"            - доход *
        "previous_sum" => "2_939_389"
        "change_percent" => 7
        "currency" => array:5 [▶]
    ]
    "income_services_stats" => array:4 [▼
        "current_sum" => "2_574_231"            - доход по услугам
        "previous_sum" => "2639038"
        "change_percent" => -2
        "currency" => array:5 [▶]
    ]
    "income_goods_stats" => array:4 [▼
        "current_sum" => "581_570"              - доход по товарам *
        "previous_sum" => "300351"
        "change_percent" => 93
        "currency" => array:5 [▶]
    ]
    "income_average_stats" => array:4 [▼
        "current_sum" => "2161.51"              - средний чек *
        "previous_sum" => "2009.15"
        "change_percent" => 7
        "currency" => array:5 [▶]
    ]
    "income_average_services_stats" => array:4 [▼
        "current_sum" => "1869.45"              - средний чек по услугам
        "previous_sum" => "1883.68"
        "change_percent" => 0
        "currency" => array:5 [▶]
    ]
    "fullness_stats" => array:3 [▼
        "current_percent" => 49.4               - средняя заполненность *
        "previous_percent" => 47
        "change_percent" => 5
    ]
    "record_stats" => array:9 [▼
        "current_completed_count" => 1394       - кол-во завершенных записей
        "current_completed_percent" => 86
        "current_pending_count" => 140
        "current_pending_percent" => 9
        "current_canceled_count" => 81          - отмененные записи
        "current_canceled_percent" => 5
        "current_total_count" => 1615           - всего записей
        "previous_total_count" => 1641
        "change_percent" => -1
    ]
    "client_stats" => array:8 [▼
        "total_count" => 25484                  - всего клиентов в филиале
        "new_count" => 153                      - новые клиенты *
        "new_percent" => 13
        "return_count" => 1029                  - повторные клиенты
        "return_percent" => 87
        "active_count" => 1182                  - активные клиенты
        "lost_count" => 355                     - потерянные клиенты
        "lost_percent" => 1
    ]
 */

// Получить список записей - для заполняемости
// https://api.yclients.com/api/v1/records/{company_id}
