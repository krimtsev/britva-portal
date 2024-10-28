<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
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
    private function isRemoveStaff($data): bool
    {
        return $data["is_fired"] || $data["is_deleted"] ; // || strtoupper($data["name"]) == "ЛИСТ ОЖИДАНИЯ"
    }

    /**
     * Получение списка сотрудников
     * @return false | array<string[]>
     */

    public function getStaff() {
        try {
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
                $staff[$id]["avatar_big"] = $one["avatar_big"];

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

            $response = $this->httpWithHeaders()->get($url);

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

            $response = $this->httpWithHeaders()->get($url);

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

            $response = $this->httpWithHeaders()->get($url);

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

            // Услуги

            $FILTER_IDS = [

				// BRITVA

                // 5778125,  // ACUMEN
				// 1846923,  // BLACK MASK
				// 9771809,  // DEPOT
				// 7188703,  // MGC QUICK SPA
				// 6536565,  // VOLCANO
				// 12724278, // ВОССТАНАВЛИВАЮЩАЯ ТЕРАПИЯ ДЛЯ ЛИЦА RHEA
				// 13621893, // ВОССТАНАВЛИВАЮЩАЯ ТЕРАПИЯ ДЛЯ ЛИЦА RHEA + ПАТЧИ
				// 8220685,  // КОМПЛЕКС ПО УХОДУ ЗА КОЖЕЙ ОТ LONDON GROOMING
				// 10052100, // КОМПЛЕКС ПО УХОДУ ЗА КОЖЕЙ ОТ LONDON GROOMING (СО СКРАБОМ)
				// 10823857, // ЛИМФОДРИНАЖНЫЙ МАССАЖ
				// 10802729, // МАССАЖ ЛИЦА
				// 10802743, // МАССАЖ ЛИЦА + ПИЛИНГ + УЛЬТРАЗВУКОВАЯ ЧИСТКА + АЛЬГИНАТНАЯ МАСКА + УВЛАЖНЯЮЩИЙ КРЕМ
				// 10802739, // ПИЛИНГ + УЛЬТРАЗВУКОВАЯ ЧИСТКА + АЛЬГИНАТНАЯ МАСКА + УВЛАЖНЯЮЩИЙ КРЕМ
				// 10823866, // РАССЛАБЛЯЮЩИЙ МАССАЖ
				// 10802726, // УВЛАЖНЯЮЩАЯ МАСКА
				// 10802733, // УЛЬТРАЗВУКОВАЯ ЧИСТКА + АЛЬГИНАТНАЯ МАСКА + УВЛАЖНЯЮЩИЙ КРЕМ
				// 13621869, // УХОД ВОКРУГ ГЛАЗ RHEA

				// 10659272, // ART HAIR TATTOO
				// 1928689,  // HAIR TATTOO
				// 1510491,  // ВОСК // КОМПЛЕКС
				// 1510490,  // ВОСК // УШИ + НОС
				// 5778126,  // ВОСК // БРОВИ
				// 1510488,  // ВОСК // ЩЕКИ
				// 8424387,  // ДЕТОКС КОЖИ ГОЛОВЫ GRAHAM HILL
				// 12034752, // КОМПЛЕКСНЫЙ УХОД ЗА КОЖЕЙ ГОЛОВЫ И ВОЛОСАМИ ОТ SOLOMON’S
				// 12320049, // КОМПЛЕКСНЫЙ УХОД ЗА КОЖЕЙ ГОЛОВЫ И ВОЛОСАМИ ОТ SYSTEM 4
				// 12863952, // КОМПЛЕКСНЫЙ УХОД ЗА КОЖЕЙ ГОЛОВЫ И ВОЛОСАМИ ОТ THEO
				// 13695706, // КОРРЕКЦИЯ + ОКРАШИВАНИЕ БРОВЕЙ
				// 13695709, // КОРРЕКЦИЯ БРОВЕЙ
				// 2695459,  // ОКАНТОВКА ГОЛОВЫ
				// 12902309, // ОКРАШИВАНИЕ БРОВЕЙ
				// 5769238,  // ПАТЧИ
				// 6540050,  // ТОНИРОВАНИЕ БОРОДЫ
				// 6540052,  // ТОНИРОВАНИЕ ГОЛОВЫ
				// 1510473,  // УКЛАДКА БЕЗ СТРИЖКИ

				// SODA

                // НОГТЕВОЙ СЕРВИС

                11702882, // МУЖСКОЙ БРАЗИЛЬСКИЙ МАНИКЮР
                12535794, // МУЖСКОЙ БРАЗИЛЬСКИЙ МАНИКЮР // ТОП-МАСТЕР
                13509749, // МУЖСКОЙ БРАЗИЛЬСКИЙ МАНИКЮР // БРЕНД-МАСТЕР
                11702880, // МУЖСКОЙ БРАЗИЛЬСКИЙ ПЕДИКЮР
                11702897, // МУЖСКОЙ БРАЗИЛЬСКИЙ ПЕДИКЮР // ТОП-МАСТЕР
                13509747, // МУЖСКОЙ БРАЗИЛЬСКИЙ ПЕДИКЮР // БРЕНД-МАСТЕР
                11702873, // МУЖСКОЙ SMART-ПЕДИКЮР
                11702887, // МУЖСКОЙ SMART-ПЕДИКЮР // ТОП-МАСТЕР
                13509746, // МУЖСКОЙ SMART-ПЕДИКЮР // БРЕНД-МАСТЕР
                11702783, // ЖЕНСКИЙ БРАЗИЛЬСКИЙ МАНИКЮР
                11702809, // ЖЕНСКИЙ БРАЗИЛЬСКИЙ МАНИКЮР // ТОП-МАСТЕР
                13509507, // ЖЕНСКИЙ БРАЗИЛЬСКИЙ МАНИКЮР // БРЕНД-МАСТЕР
                11702841, // ЖЕНСКИЙ БРАЗИЛЬСКИЙ ПЕДИКЮР
                11702906, // ЖЕНСКИЙ БРАЗИЛЬСКИЙ ПЕДИКЮР // ТОП-МАСТЕР
                13509611, // ЖЕНСКИЙ БРАЗИЛЬСКИЙ ПЕДИКЮР // БРЕНД-МАСТЕР
                11702840, // ЖЕНСКИЙ SMART-ПЕДИКЮР
                11702904, // ЖЕНСКИЙ SMART-ПЕДИКЮР // ТОП-МАСТЕР
                13509607, // ЖЕНСКИЙ SMART-ПЕДИКЮР // БРЕНД-МАСТЕР

                13812214, // ЛЕЧЕБНЫЕ СИСТЕМЫ (P.SHINE, IBX)
                13812216, // ЛЕЧЕБНЫЕ СИСТЕМЫ (P.SHINE, IBX) // ТОП-МАСТЕР
                13509652, // ЛЕЧЕБНЫЕ СИСТЕМЫ (P.SHINE, IBX) // БРЕНД-МАСТЕР
                11702784, // ЛЕЧЕБНОЕ ПОКРЫТИЕ BANDI
                11702808, // ЛЕЧЕБНОЕ ПОКРЫТИЕ BANDI // ТОП-МАСТЕР
                13509653, // ЛЕЧЕБНОЕ ПОКРЫТИЕ BANDI // БРЕНД-МАСТЕР
                11702795, // УКРЕПЛЯЮЩЕЕ ПОКРЫТИЕ
                14390484, // УКРЕПЛЯЮЩЕЕ ПОКРЫТИЕ // ТОП-МАСТЕР
                13509655, // УКРЕПЛЯЮЩЕЕ ПОКРЫТИЕ // БРЕНД-МАСТЕР

                11702779, // ВЫРАВНИВАНИЕ БАЗОЙ
                11702767, // УКРЕПЛЕНИЕ ГЕЛЕМ
                11702758, // SPA BANDI НА ШВЕЙЦАРСКИХ ТРАВАХ (РУКИ)
                11702754, // SPA BANDI НА ШВЕЙЦАРСКИХ ТРАВАХ + УВЛАЖНЯЮЩАЯ МАСКА (РУКИ)
                11702872, // SPA BANDI НА ШВЕЙЦАРСКИХ ТРАВАХ (НОГИ)
                11702884, // SPA BANDI НА ШВЕЙЦАРСКИХ ТРАВАХ + УВЛАЖНЯЮЩАЯ МАСКА (НОГИ)
                11702761, // ЭКСПРЕСС УКРЕПЛЕНИЕ НОГТЕЙ
                11702753, // ЭКСПРЕСС-SPA ДЛЯ РУК
                11702755, // ЭКСПРЕСС-SPA ДЛЯ НОГ
                13724685, // СКРАБ + ХОЛОДНЫЙ ПАРАФИН
                11702777, // ХОЛОДНАЯ ПАРАФИНОТЕРАПИЯ
                11702765, // СКРАБ
                11702770, // ЖИДКОЕ ЛЕЗВИЕ
                11702776, // ПОЛИРОВКА НОГТЕЙ
                11702778, // МАССАЖ РУК
                13821569, // МАССАЖ НОГ

                11702888, // ПРОСТОЙ ДИЗАЙН 1 ПАЛЕЦ
                11702891, // СЛОЖНЫЙ ДИЗАЙН 1 ПАЛЕЦ
                11702895, // ХУДОЖЕСТВЕННАЯ РОСПИСЬ 1 ПАЛЕЦ
                11725506, // ФРЕНЧ

                // БРОВИСТЫ

                11702825, // СЧАСТЬЕ ДЛЯ БРОВЕЙ
                13510355, // СЧАСТЬЕ ДЛЯ БРОВЕЙ (BOTOX) // ТОП-МАСТЕР
                11702829, // ЛАМИНИРОВАНИЕ БРОВЕЙ
                13510356, // ЛАМИНИРОВАНИЕ БРОВЕЙ // ТОП-МАСТЕР
                11742680, // ЛАМИНИРОВАНИЕ РЕСНИЦ
                13510336, // ЛАМИНИРОВАНИЕ РЕСНИЦ // ТОП-МАСТЕР
                11702923, // ОКРАШИВАНИЕ РЕСНИЦ
                13510338, // ОКРАШИВАНИЕ РЕСНИЦ // ТОП-МАСТЕР
                13821651, // ВОСК ВЕРХНЯЯ ГУБА
                13821658, // ВОСК ВЕРХНЯЯ ГУБА // ТОП-МАСТЕР

                // СТИЛИСТЫ

                11703026, // ПРИЧЕСКА (СРЕДНИЕ)
                11703068, // ПРИЧЕСКА (СРЕДНИЕ) // ТОП-МАСТЕР
                11703397, // ПРИЧЕСКА (СРЕДНИЕ) // АРТ
                11703029, // ПРИЧЕСКА (ДЛИННЫЕ)
                11703069, // ПРИЧЕСКА (ДЛИННЫЕ) // ТОП-МАСТЕР
                11703401, // ПРИЧЕСКА (ДЛИННЫЕ) // АРТ
                11703063, // УКЛАДКА (КОРОТКИЕ)
                11703088, // УКЛАДКА (КОРОТКИЕ) // ТОП-МАСТЕР
                11703421, // УКЛАДКА (КОРОТКИЕ) // АРТ
                11703062, // УКЛАДКА (СРЕДНИЕ)
                11703083, // УКЛАДКА (СРЕДНИЕ) // ТОП-МАСТЕР
                11703418, // УКЛАДКА (СРЕДНИЕ) // АРТ
                11703064, // УКЛАДКА (ДЛИННЫЕ)
                11703085, // УКЛАДКА (ДЛИННЫЕ) // ТОП-МАСТЕР
                11703425, // УКЛАДКА (ДЛИННЫЕ) // АРТ
                11710205, // КИСЛОТНАЯ СМЫВКА (КОРОТКИЕ)
                11710254, // КИСЛОТНАЯ СМЫВКА (КОРОТКИЕ) // ТОП-МАСТЕР
                11710291, // КИСЛОТНАЯ СМЫВКА (КОРОТКИЕ) // АРТ
                11710211, // КИСЛОТНАЯ СМЫВКА (СРЕДНИЕ)
                11710263, // КИСЛОТНАЯ СМЫВКА (СРЕДНИЕ) // ТОП-МАСТЕР
                11710292, // КИСЛОТНАЯ СМЫВКА (СРЕДНИЕ) // АРТ
                11710227, // КИСЛОТНАЯ СМЫВКА (ДЛИННЫЕ)
                11710264, // КИСЛОТНАЯ СМЫВКА (ДЛИННЫЕ) // ТОП-МАСТЕР
                11710293, // КИСЛОТНАЯ СМЫВКА (ДЛИННЫЕ) // АРТ

                11703458, // УХОД DAVINES. КЕРАТИНОВОЕ ЧУДО (КОРОТКИЕ)
                11703459, // УХОД DAVINES. КЕРАТИНОВОЕ ЧУДО (СРЕДНИЕ)
                11703462, // УХОД DAVINES. КЕРАТИНОВОЕ ЧУДО (ДЛИННЫЕ)
                11703481, // ЭКСПРЕСС-УХОД ВЕГЕТАРИАНСКОЕ ЧУДО ОТ DAVINES (КОРОТКИЕ)
                11703486, // ЭКСПРЕСС-УХОД ВЕГЕТАРИАНСКОЕ ЧУДО ОТ DAVINES (СРЕДНИЕ)
                11703487, // ЭКСПРЕСС-УХОД ВЕГЕТАРИАНСКОЕ ЧУДО ОТ DAVINES (ДЛИННЫЕ)
                11703466, // ВОССТАНАВЛИВАЮЩИЙ УХОД (КОРОТКИЕ)
                11703468, // ВОССТАНАВЛИВАЮЩИЙ УХОД (СРЕДНИЕ)
                11703469, // ВОССТАНАВЛИВАЮЩИЙ УХОД (ДЛИННЫЕ)
                11703488, // ПИЛИНГ ГОЛОВЫ
                11703489, // ПИЛИНГ ГОЛОВЫ DAVINES
                11703673, // МАСКА
                11872906, // УХОД ЗА ВОЛОСАМИ HADAT (КОРОТКИЕ)
                11872909, // УХОД ЗА ВОЛОСАМИ HADAT (СРЕДНИЕ)
                11872912, // УХОД ЗА ВОЛОСАМИ HADAT (ДЛИННЫЕ)
                12278035, // OI LIQUID LUSTER DAVINES (КОРОТКИЕ)
                12278040, // OI LIQUID LUSTER DAVINES (СРЕДНИЕ)
                12278044, // OI LIQUID LUSTER DAVINES (ДЛИННЫЕ)
                12278049, // BOND ИНТЕНСИВНЫЙ УХОД 5 СТУПЕНЕЙ (КОРОТКИЕ)
                12278053, // BOND ИНТЕНСИВНЫЙ УХОД 5 СТУПЕНЕЙ (СРЕДНИЕ)
                12278057, // BOND ИНТЕНСИВНЫЙ УХОД 5 СТУПЕНЕЙ (ДЛИННЫЕ)
                12278061, // BOND УХОД 2 ФАЗЫ (КОРОТКИЕ)
                12278064, // BOND УХОД 2 ФАЗЫ (СРЕДНИЕ)
                12278068, // BOND УХОД 2 ФАЗЫ (ДЛИННЫЕ)
                12278070, // BOND БУСТЕР 1 ФАЗА
                12846277, // УХОДЫ KEVIN MURPHY С АМПУЛАМИ (КОРОТКИЕ)
                12846281, // УХОДЫ KEVIN MURPHY С АМПУЛАМИ (СРЕДНИЕ)
                12846282, // УХОДЫ KEVIN MURPHY С АМПУЛАМИ (ДЛИННЫЕ)
                12846288, // УХОДЫ KEVIN MURPHY ЭКСПРЕСС (КОРОТКИЕ)
                12846289, // УХОДЫ KEVIN MURPHY ЭКСПРЕСС (СРЕДНИЕ)
                12846291, // УХОДЫ KEVIN MURPHY ЭКСПРЕСС (ДЛИННЫЕ)
                12846293, // УХОДЫ KEVIN MURPHY ДЛЯ ОКРАШЕННЫХ ВОЛОС В ДЕНЬ ОКРАШИВАНИЯ (КОРОТКИЕ)
                12846294, // УХОДЫ KEVIN MURPHY ДЛЯ ОКРАШЕННЫХ ВОЛОС В ДЕНЬ ОКРАШИВАНИЯ (СРЕДНИЕ)
                12846295, // УХОДЫ KEVIN MURPHY ДЛЯ ОКРАШЕННЫХ ВОЛОС В ДЕНЬ ОКРАШИВАНИЯ (ДЛИННЫЕ)

            ];

            // Услуги в комплексе

            $SERVICES_COST = [

				// BRITVA

                // 5855572 => 1600, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК - 1600 рублей
                // 5855566 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК - 400 рулей
                // 13458944 => 2800, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT - 2800 рублей
                // 13458949 => 3200, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК - 3200 рублей
                // 5855583 => 1600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК - 1600 рублей
                // 5855560 => 1200, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN - 1200 рублей

                // 7043251 => 1200, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN // ТОП-БАРБЕР - 1200 рублей
                // 7043266 => 1600, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК // ТОП-БАРБЕР - 1600 рублей
                // 7043330 => 1600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК // ТОП-БАРБЕР - 1600 рублей
                // 13458978 => 3200, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК // ТОП-БАРБЕР - 3200 рублей
                // 13458971 => 2800, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT // ТОП-БАРБЕР - 2800 рублей
                // 7043262 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // ТОП-БАРБЕР - 400 рублей

                // 8337664 => 1200, // МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN // БРЕНД-БАРБЕР - 1200 рублей
                // 8337665 => 1600, // МУЖСКАЯ СТРИЖКА + BLACK MASK/VOLCANO/ACUMEN + ВОСК // БРЕНД-БАРБЕР - 1600 рублей
                // 8337669 => 1600, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + BLACK MASK/VOLCANO/ACUMEN + ВОСК // БРЕНД-БАРБЕР - 1600 рублей
                // 13458996 => 3200, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК // БРЕНД-БАРБЕР - 3200 рублей
                // 13458994 => 2800, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT // БРЕНД-БАРБЕР - 2800 рублей
                // 8337666 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // БРЕНД-БАРБЕР - 400 рублей

                // 12320307 => 2800, // МУЖСКАЯ СТРИЖКА + DEPOT // ЭКСПЕРТ - 2800 рублей
                // 12320304 => 3200, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + DEPOT + ВОСК // ЭКСПЕРТ - 3200 рублей
                // 12320306 => 400, // МУЖСКАЯ СТРИЖКА + МОДЕЛИРОВАНИЕ БОРОДЫ + ВОСК // ЭКСПЕРТ - 400 рублей

				// НОГТЕВОЙ СЕРВИС

                13821260 => 1200, // СНЯТИЕ ГЕЛЬ-ЛАКА + МАНИКЮР + УКРЕПЛЕНИЕ ГЕЛЕМ + ПОКРЫТИЕ ГЕЛЬ-ЛАКОМ
                13821309 => 1200, // СНЯТИЕ ГЕЛЬ-ЛАКА + МАНИКЮР + УКРЕПЛЕНИЕ ГЕЛЕМ + ПОКРЫТИЕ ГЕЛЬ-ЛАКОМ // ТОП-МАСТЕР
                13509468 => 1200, // СНЯТИЕ ГЕЛЬ-ЛАКА + МАНИКЮР + УКРЕПЛЕНИЕ ГЕЛЕМ + ПОКРЫТИЕ ГЕЛЬ-ЛАКОМ // БРЕНД-МАСТЕР

                13821259 => 1200, // СНЯТИЕ ГЕЛЬ-ЛАКА + МАНИКЮР + КАМУФЛИРУЮЩЕЕ УКРЕПЛЕНИЕ ГЕЛЕМ
                13821308 => 1200, // СНЯТИЕ ГЕЛЬ-ЛАКА + МАНИКЮР + КАМУФЛИРУЮЩЕЕ УКРЕПЛЕНИЕ ГЕЛЕМ // ТОП-МАСТЕР
                13509467 => 1200, // СНЯТИЕ ГЕЛЬ-ЛАКА + МАНИКЮР + КАМУФЛИРУЮЩЕЕ УКРЕПЛЕНИЕ ГЕЛЕМ // БРЕНД-МАСТЕР

                11703400 => 400, // СНЯТИЕ ГЕЛЬ-ЛАКА + МАНИКЮР + ВЫРАВНИВАНИЕ + ПОКРЫТИЕ ГЕЛЬ-ЛАК
                11703411 => 400, // СНЯТИЕ ГЕЛЬ-ЛАКА + МАНИКЮР + ВЫРАВНИВАНИЕ + ПОКРЫТИЕ ГЕЛЬ-ЛАК // ТОП-МАСТЕР
                13509434 => 400, // СНЯТИЕ ГЕЛЬ-ЛАКА + МАНИКЮР + ВЫРАВНИВАНИЕ + ПОКРЫТИЕ ГЕЛЬ-ЛАК // БРЕНД-МАСТЕР

                13821256 => 2300, // СНЯТИЕ ГЕЛЬ-ЛАКА + SMART-ПЕДИКЮР + ПОКРЫТИЕ ГЕЛЬ-ЛАК
                13821306 => 2600, // СНЯТИЕ ГЕЛЬ-ЛАКА + SMART-ПЕДИКЮР + ПОКРЫТИЕ ГЕЛЬ-ЛАК // ТОП-МАСТЕР
                13509463 => 2800, // СНЯТИЕ ГЕЛЬ-ЛАКА + SMART-ПЕДИКЮР + ПОКРЫТИЕ LUXIO, BANDI // БРЕНД-МАСТЕР

                // БРОВИСТЫ

                11703436 => 700, // ОКРАШИВАНИЕ + СЧАСТЬЕ ДЛЯ БРОВЕЙ
                13510214 => 700, // ОКРАШИВАНИЕ + СЧАСТЬЕ ДЛЯ БРОВЕЙ // ТОП-МАСТЕР

                11703434 => 2500, // ЛАМИНИРОВАНИЕ + СЧАСТЬЕ ДЛЯ БРОВЕЙ
                13510216 => 2800, // ЛАМИНИРОВАНИЕ + СЧАСТЬЕ ДЛЯ БРОВЕЙ // ТОП-МАСТЕР

                11703432 => 700, // АРХИТЕКТУРА БРОВЕЙ + СЧАСТЬЕ ДЛЯ БРОВЕЙ
                13510219 => 700, // АРХИТЕКТУРА БРОВЕЙ + СЧАСТЬЕ ДЛЯ БРОВЕЙ // ТОП-МАСТЕР

                // СТИЛИСИТЫ

                11703313 => 3000, // СТРИЖКА + КЕРАТИНОВОЕ ЧУДО ОТ DAVINES (КОРОТКИЕ)
                11703314 => 4200, // СТРИЖКА + КЕРАТИНОВОЕ ЧУДО ОТ DAVINES (СРЕДНИЕ)
                11703315 => 6000, // СТРИЖКА + КЕРАТИНОВОЕ ЧУДО ОТ DAVINES (ДЛИННЫЕ)

                11703295 => 3000, // СТРИЖКА + КЕРАТИНОВОЕ ЧУДО ОТ DAVINES (КОРОТКИЕ) // ТОП-СТИЛИСТ
                11703286 => 4200, // СТРИЖКА + КЕРАТИНОВОЕ ЧУДО ОТ DAVINES (СРЕДНИЕ) // ТОП-СТИЛИСТ
                11703293 => 6000, // СТРИЖКА + КЕРАТИНОВОЕ ЧУДО ОТ DAVINES (ДЛИННЫЕ) // ТОП-СТИЛИСТ

                11703312 => 2200, // СТРИЖКА + ЭКСПРЕСС-УХОД ВЕГЕТАРИАНСКОЕ ЧУДО ОТ DAVINES (КОРОТКИЕ)
                11703311 => 3000, // СТРИЖКА + ЭКСПРЕСС-УХОД ВЕГЕТАРИАНСКОЕ ЧУДО ОТ DAVINES (СРЕДНИЕ)
                11703309 => 3400, // СТРИЖКА + ЭКСПРЕСС-УХОД ВЕГЕТАРИАНСКОЕ ЧУДО ОТ DAVINES (ДЛИННЫЕ)

                11703235 => 2200, // СТРИЖКА + ЭКСПРЕСС-УХОД ВЕГЕТАРИАНСКОЕ ЧУДО ОТ DAVINES (КОРОТКИЕ) // ТОП-СТИЛИСТ
                11703224 => 3000, // СТРИЖКА + ЭКСПРЕСС-УХОД ВЕГЕТАРИАНСКОЕ ЧУДО ОТ DAVINES (СРЕДНИЕ) // ТОП-СТИЛИСТ
                11703247 => 3400, // СТРИЖКА + ЭКСПРЕСС-УХОД ВЕГЕТАРИАНСКОЕ ЧУДО ОТ DAVINES (ДЛИННЫЕ) // ТОП-СТИЛИСТ
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

        $response = $this->httpWithHeaders()->get($url);

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

        $response = $this->httpWithHeaders()->get($url);

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

            $response = $this->httpWithHeaders()->get($url);

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

            $response = $this->httpWithHeaders()->get($url);

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
