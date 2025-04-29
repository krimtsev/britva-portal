<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;
use Carbon\Carbon;

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

    public function __construct($params = [])
    {
        $this->app_token = env('YCLIENTS_APP_TOKEN', '');
        $this->partner_token = env('YCLIENTS_PARTNER_TOKEN', '');

        $this->updateParams($params);
    }

    public function setCompanyId($id) {
        $this->company_id = $id;
    }

    public function updateParams($params) {
        if (array_key_exists('company_id', $params)) {
            $this->company_id = $params["company_id"];
        }

        if (array_key_exists('start_date', $params)) {
            $this->start_date = $params["start_date"];
        }

        if (array_key_exists('end_date', $params)) {
            $this->end_date = $params["end_date"];
        }
    }

    private function httpWithHeaders(): \Illuminate\Http\Client\PendingRequest
    {
        $isUseRetry = (bool) env('HTTP_USE_RETRY', false);
        $isHttpDebug = (bool) env('HTTP_DEBUG', false);

        $http = Http::withHeaders([
            "Accept"          => "application/vnd.yclients.v2+json",
            "Content-Type"    => "application/json",
            "Authorization"   => sprintf("Bearer %s, User %s", $this->partner_token, $this->app_token),
            "Idempotency-Key" => Str::uuid()->toString(),
            "Connection"      => "close"
        ])->withOptions([
            "verify" => false,
        ]);

        if ($isUseRetry) {
            $http->retry(2, 5000);
        }

        if ($isHttpDebug) {
            $http->withMiddleware(function ($handler) {
                return function ($request, array $options) use ($handler) {
                    Log::channel('http')->info('Request', [
                        'method'  => $request->getMethod(),
                        'url'     => (string) $request->getUri(),
                        'headers' => $request->getHeaders(),
                        'body'    => (string) $request->getBody(),
                    ]);
                    return $handler($request, $options);
                };
            });
        }

        return $http;
    }

    private function httpGet($url) {
        $response = $this->httpWithHeaders()->get($url);

        $isHttpDebug = (bool) env('HTTP_DEBUG', false);
        if ($isHttpDebug) {
            Log::channel('http')->info('Response', [
                'method'  => 'GET',
                'url'     => $url,
                'status'  => $response->status(),
                'body'    => $response->body(),
                'headers' => $response->headers(),
            ]);
        }

        return $response;
    }

    /**
     * Фильтр уволенных сотрудников, удаленных сотрудников, лист ожидания. 2191383
     */
    private function isRemoveStaff($data): bool
    {
        return $data["is_fired"] || $data["is_deleted"] ; // || strtoupper($data["name"]) == "ЛИСТ ОЖИДАНИЯ"
    }

    /**
     * Получение списка сотрудников
     * @return false | array<string[]>
     */

    public function getStaff($params = []) {
        try {
            $url = sprintf("https://api.yclients.com/api/v1/company/%s/staff", $this->company_id);

            $response = $this->httpGet($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            $withRemoveStaff = array_key_exists('withRemoveStaff', $params);

            $staff = [];

            foreach ($response["data"] as $one) {
                if(!$withRemoveStaff && $this->isRemoveStaff($one)) {
                    continue;
                }
                $id = $one["id"];

                $staff[$id]["id"] = $one["id"];
                $staff[$id]["name"] = $one["name"];
                $staff[$id]["specialization"] = $one["specialization"];
                $staff[$id]["avatar_big"] = $one["avatar_big"];

                if (array_key_exists('is_fired', $one)) {
                    $staff[$id]["is_fired"] = $one["is_fired"];
                }

                if (isset($one["user"]["phone"])) {
                    $staff[$id]["phone"] = $one["user"]["phone"];
                } else {
                    $staff[$id]["phone"] = "";
                }
            }

            return $staff;

        } catch (Throwable $e) {
            report($e->getMessage());
            return false;
        }
    }

    /**
     * Получение данных по сотруднику
     * @return false | array<string[]>
     */

    public function getStaffData($staff) {
        try {
            $url = sprintf("https://api.yclients.com/api/v1/company/%s/staff/%s", $this->company_id, $staff);

            $response = $this->httpGet($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            $data = $response["data"];

            return [
                "id"             => $data["id"],
                "name"           => $data["name"],
                "specialization" => $data["specialization"],
            ];

        } catch (Throwable $e) {
            report($e->getMessage());
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

            $response = $this->httpGet($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            return [
                "average_sum" => (float) $response["data"]["income_average_stats"]["current_sum"],
                "fullness" => $response["data"]["fullness_stats"]["current_percent"],
                "new_client" => $response["data"]["client_stats"]["new_count"],
                "return_client" => $response["data"]["client_stats"]["return_count"],
                "income_total" => $response["data"]["income_total_stats"]["current_sum"],
                "income_goods" => $response["data"]["income_goods_stats"]["current_sum"],
            ];

        } catch (Throwable $e) {
            report($e->getMessage());

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

            $response = $this->httpGet($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            return [
                "average_sum" => $response["data"]["income_average_stats"]["current_sum"],
                "fullness" => $response["data"]["fullness_stats"]["current_percent"],
                "new_client" => $response["data"]["client_stats"]["new_count"],
                "income_total" => $response["data"]["income_total_stats"]["current_sum"],
                "income_goods" => $response["data"]["income_goods_stats"]["current_sum"],
            ];

        } catch (Throwable $e) {
            report($e->getMessage());
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

            $response = $this->httpGet($url);

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
            report($e->getMessage());
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

            $response = $this->httpGet($url);

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
            report($e->getMessage());
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

            $FILTER_IDS = [

				// Дополнительные услуги и уходы в сети 44011 (основная сеть)

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
                15412256, // КОМПЛЕКС ПО УХОДУ ЗА КОЖЕЙ ЛИЦА И БОРОДЫ
                16116841, // ИНТЕНСИВНЫЙ УХОД ЗА КОЖЕЙ ЛИЦА VOLCANO

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
                13695706, // КОРРЕКЦИЯ + ОКРАШИВАНИЕ БРОВЕЙ
                13695709, // КОРРЕКЦИЯ БРОВЕЙ
                2695459,  // ОКАНТОВКА ГОЛОВЫ
                12902309, // ОКРАШИВАНИЕ БРОВЕЙ
                5769238,  // ПАТЧИ
                6540050,  // ТОНИРОВАНИЕ БОРОДЫ
                6540052,  // ТОНИРОВАНИЕ ГОЛОВЫ
                1510473,  // УКЛАДКА БЕЗ СТРИЖКИ
                15355703, // ДЕТОКС КОЖИ ГОЛОВЫ ОТ REUZEL
                15538601, // ТОНИРОВАНИЕ ГОЛОВЫ + ТОНИРОВАНИЕ БОРОДЫ
                15935091, // ПИЛИНГ КОЖИ ГОЛОВЫ

				// Дополнительные услуги и уходы в сети 772294 (сеть регионы)

                11414217, // ACUMEN
                11414218, // BLACK MASK
                11414220, // DEPOT
                11414221, // MGC QUICK SPA
                11414222, // VOLCANO
                11414223, // КОМПЛЕКС ПО УХОДУ ЗА КОЖЕЙ ОТ LONDON GROOMING
                11414224, // КОМПЛЕКС ПО УХОДУ ЗА КОЖЕЙ ОТ LONDON GROOMING (СО СКРАБОМ)

                11414128, // HAIR TATTOO
                11414132, // ВОСК // КОМПЛЕКС
                11414133, // ВОСК // УШИ + НОС
                11414130, // ВОСК // БРОВИ
                11414134, // ВОСК // ЩЕКИ
                11414135, // ДЕТОКС КОЖИ ГОЛОВЫ GRAHAM HILL
                11414136, // ОКАНТОВКА ГОЛОВЫ
                11414137, // ПАТЧИ
                11414138, // ТОНИРОВАНИЕ БОРОДЫ
                11414139, // ТОНИРОВАНИЕ ГОЛОВЫ
				11414140, // УКЛАДКА БЕЗ СТРИЖКИ
            ];

            $SERVICES_COST = [

				// Услуги в комплексе основная сеть 44011

                5855572 => 2100, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК
                5855566 => 600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК
                13458944 => 3500, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT
                13458949 => 4100, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК
                5855583 => 2100, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК
                5855560 => 1500, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN

                7043251 => 1500, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN // ТОП-БАРБЕР
                7043266 => 2100, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК // ТОП-БАРБЕР
                7043330 => 2100, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК // ТОП-БАРБЕР
                13458978 => 4100, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК // ТОП-БАРБЕР
                13458971 => 3500, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT // ТОП-БАРБЕР
                7043262 => 600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // ТОП-БАРБЕР

                8337664 => 1500, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN // БРЕНД-БАРБЕР
                8337665 => 2100, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК // БРЕНД-БАРБЕР
                8337669 => 2100, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК // БРЕНД-БАРБЕР
                13458996 => 4100, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК // БРЕНД-БАРБЕР
                13458994 => 3500, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT // БРЕНД-БАРБЕР
                8337666 => 600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // БРЕНД-БАРБЕР

                14514601 => 1500, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN // БРЕНД-БАРБЕР+
                14514599 => 2100, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК // БРЕНД-БАРБЕР+
                14514600 => 2100, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК // БРЕНД-БАРБЕР+
                15320508 => 4100, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК // БРЕНД-БАРБЕР+
                15320509 => 3500, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT // БРЕНД-БАРБЕР+
                14514595 => 600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // БРЕНД-БАРБЕР+

                12320307 => 3500, // МУЖСКАЯ СТРИЖКА + DEPOT // ЭКСПЕРТ
                12320304 => 4100, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК // ЭКСПЕРТ
                12320306 => 600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // ЭКСПЕРТ

				// Услуги в комплексе сеть регионы 772294

				11414147 => 1200, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN
				11414152 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК
				11414148 => 1600, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК
				11414151 => 1600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК

				11414167 => 1200, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN // ТОП-БАРБЕР
				11414172 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // ТОП-БАРБЕР
				11414169 => 1600, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК // ТОП-БАРБЕР
				11414170 => 1600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК // ТОП-БАРБЕР

				11414156 => 1200, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN // БРЕНД-БАРБЕР
				11414160 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // БРЕНД-БАРБЕР
				11414157 => 1600, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК // БРЕНД-БАРБЕР
				11414158 => 1600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК // БРЕНД-БАРБЕР

            ];

            $SERVICES_COST_IDS = array_keys($SERVICES_COST);

            $url = sprintf("https://api.yclients.com/api/v1/records/%s?%s", $this->company_id, $query);

            $response = $this->httpGet($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            $total = 0;

            foreach ($response["data"] as $one) {
                if (!is_array($one["services"]) || count($one["services"]) === 0)
                    continue;

                foreach ($one["services"] as $service) {
                    if (in_array($service["id"], $FILTER_IDS)) {
                        $total += $service["manual_cost"];
                    }

                    if (in_array($service["id"], $SERVICES_COST_IDS)) {
                        $total += $SERVICES_COST[$service["id"]];
                    }
                }
            }

            return $total;

        } catch (Throwable $e) {
            report($e->getMessage());
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

        $url = sprintf("https://api.yclients.com/api/v1/company/%s/services?%s", $this->company_id, $query);

        $response = $this->httpGet($url);

        $response = $response->json($key = null);

        if(!$response["success"]) {
            return false;
        }

        return $response["data"];
    }

    function getClientNameByTelnum($caller_number) {
        $query = http_build_query([
            "phone" => $caller_number,
        ]);

        $url = sprintf("https://api.yclients.com/api/v1/clients/%s?%s", $this->company_id, $query);

        $response = $this->httpGet($url);

        $response = $response->json($key = null);

        if(!$response["success"]) {
            return false;
        }

        return $response["data"];
    }

    function getRoyaltyByStaffId($staff_id) {
        try {
            $query = http_build_query([
                "start_date" => $this->start_date,
                "end_date"   => $this->end_date,
                "count"      => $this->count,
                "staff_id"   => $staff_id,
            ]);

            $url = sprintf("https://api.yclients.com/api/v1/records/%s?%s", $this->company_id, $query);

            $response = $this->httpGet($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            $result = [];

            foreach ($response["data"] as $one) {
                if (!is_array($one["services"]) || count($one["services"]) === 0)
                    continue;

                $date = Carbon::parse($one["date"])->format('Y-m-d');

                if (!array_key_exists($date, $result)){
                    $result[$date] = 1;
                } else {
                    $result[$date] += 1;
                }
            }

            return $result;

        } catch (Throwable $e) {
            report($e->getMessage());
            return false;
        }
    }

    public function getRecordsList() {
        try {
            $query = http_build_query([
                "start_date" => $this->start_date,
                "end_date"   => $this->end_date,
                "count"      => $this->count,
            ]);

            $url = sprintf("https://api.yclients.com/api/v1/records/%s?%s", $this->company_id, $query);

            $response = $this->httpGet($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            $result = [];

            foreach ($response["data"] as $one) {
                if (!is_array($one["services"]) || count($one["services"]) === 0 ||
                    !is_array($one["client"]) || !array_key_exists("id", $one["client"]))
                    continue;

                try {
                    $clientId = $one["client"]["id"];

                    if (!array_key_exists($clientId, $result)) {
                        $result[$clientId] = [
                            "id"       => $clientId,
                            "name"     => $one["client"]["name"],
                            "phone"    => $one["client"]["phone"],
                            "services" => array_map(function($service) { return $service["title"]; }, $one["services"]),
                        ];
                    }
                } catch (Throwable $e) {
                    report(json_encode($one));
                    continue;
                }
            }

            return $result;

        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }

    public function getVisitedForPeriod($ids) {
        try {
            $values = array_unique($ids);

            $body = json_encode([
                "page"       => 1,
                "page_size"  => $this->count,
                "fields"     => [
                    "id",
                    "name",
                    "phone",
                    "visits_count",
                    "last_visit_date"
                ],
                "filters"    => [
                    [
                        "type"   => "id",
                        "state"  => [
                            "value" => $values
                        ],
                    ]
                ],
                "order_by"   => "last_visit_date",
                "order_by_direction" => "desc",
                "operation"  => "AND"
            ]);

            $url = sprintf("https://api.yclients.com/api/v1/company/%s/clients/search", $this->company_id);

            $response = $this->httpWithHeaders()
                ->withBody($body, 'application/json')
                ->post($url);

            $response = $response->json($key = null);

            if(!$response["success"]) {
                return false;
            }

            return $response["data"];

        } catch (Throwable $e) {
            report($e->getMessage());
            return false;
        }
    }
}
