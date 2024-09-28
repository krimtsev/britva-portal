<?php

namespace App\Http\Controllers\Mango;

use App\Http\Controllers\Controller;
use App\Http\Services\MangoService;
use App\Http\Services\ReportService;
use App\Http\Services\YclientsService;
use App\Models\MangoBlacklist;
use App\Models\Partner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Throwable;

class MangoController extends Controller
{
    const CACHE_MANGO_KEY = "mango_last_telnums_call";
    const CACHE_YCLIENTS_KEY = "yclients_last_names_call";
    const TEST_CALL_KEY = "mango_test_call";
    const BLACKLIST_ENABLED = true;

    public function index(Request $request)
    {
        if ($request->input("flush")) {
            Cache::flush();
        }

        $date = Carbon::now();
        $end_date = $date->format('d.m.Y H:i:s');
        $start_date = $date->subMinutes(30)->format('d.m.Y H:i:s');

        $params = [
            "start_date"     => $start_date,
            "end_date"       => $end_date,
            "context_status" => 0
        ];

        $data = [];
        try {
            $service = new MangoService($params);

            $data = $service->get();
        } catch (Throwable $e) {
            ReportService::send("[MANGO] get", $e->getMessage());
        }

        if (array_key_exists("error", $data)) return $data;

        $table = [];

        list($cachedTelnumsList, $ids) = self::getCachedTelnums($start_date);

        $telnumsList = self::getTelnumsList();

        if (count($data) !== 0) {
            $data = self::BLACKLIST_ENABLED
                ? self::filterBlacklist($data[0]["list"])
                : $data[0]["list"];

            foreach ($data as $one) {
                $called_number = $one["called_number"];

                if (!array_key_exists($called_number, $telnumsList)) {
                    continue;
                }

                $timestamp = $one["context_start_time"];
                $id = self::createID($timestamp, $called_number);

                if (in_array($id, $ids)) {
                    continue;
                }

                $cachedTelnumsList[] = [
                    "timestamp"     => $timestamp,
                    "called_number" => $called_number
                ];

                $client_name = "";
                try {
                    $client_name = $this->getClientName(
                        $telnumsList[$called_number]["yclients_id"],
                        $one["caller_number"],
                        $start_date
                    );
                } catch (Throwable $e) {
                    ReportService::send("[YCLIENTS] getClientName", $e->getMessage());
                }

                $tg_pay_end = array_key_exists("tg_pay_end", $telnumsList[$called_number])
                    ? $telnumsList[$called_number]["tg_pay_end"]
                    : "";

                $table[] = [
                    "name"               => $telnumsList[$called_number]["name"],
                    "client_name"        => $client_name,
                    "caller_number"      => $one["caller_number"],
                    "called_number"      => $called_number,
                    "context_start_time" => Carbon::createFromTimestamp($one["context_start_time"])->format('d.m.Y H:i:s'),
                    "call_duration"      => $one["duration"],
                    "tg_chat_id"         => $telnumsList[$called_number]["tg_chat_id"],
                    "isActive"           => $tg_pay_end && Carbon::parse($tg_pay_end) >= Carbon::now()
                ];
            }
        }

        Cache::put(self::CACHE_MANGO_KEY, $cachedTelnumsList, Carbon::now()->addMinutes(40));

        /*
         * Добавляем тестовый звонок
         */
        if(Cache::has(self::TEST_CALL_KEY)) {
            $table[] = Cache::get(self::TEST_CALL_KEY);
            Cache::forget(self::TEST_CALL_KEY);
        }

        return $table;
    }

    protected function getCachedTelnums($start_date): array
    {
        $timestamp = Carbon::parse($start_date);
        $cached_list = Cache::get(self::CACHE_MANGO_KEY, []);

        $telnums_list = [];
        $ids = [];

        foreach ($cached_list as $one) {
            if (Carbon::createFromTimestamp($one["timestamp"]) > $timestamp) {
                $telnums_list[] = $one;
                $ids[] = self::createID($one["timestamp"], $one["called_number"]);
            }
        }

        return [$telnums_list, $ids];
    }

    protected function getTelnumsList(): array
    {
        $telnumsList = [];

        $partners = Partner::select(
            "name",
            "yclients_id",
            "mango_telnum",
            "tg_chat_id",
            "tg_active",
            "tg_pay_end",
            "lost_client_days",
            "repeat_client_days",
            "new_client_days",
        )
            ->where('yclients_id', '<>', "")
            ->where('disabled', '<>', 1)
            ->where("tg_active", 1)
            ->get();

        foreach ($partners as $partner) {
            $telnumsList[$partner->mango_telnum] = $partner->toArray();
        }

        return $telnumsList;
    }

    protected function createID($timestamp, $called_number): string
    {
        return "{$timestamp}_{$called_number}";
    }

    protected function getClientName($company_id, $caller_number, $start_date) {
        $timestamp = Carbon::parse($start_date);
        $cached_list = Cache::get(self::CACHE_YCLIENTS_KEY, []);
        $telnums_list = [];
        $name = "";

        if (array_key_exists($caller_number, $cached_list)) {
            $name = $cached_list[$caller_number]["name"];
        }

        foreach ($cached_list as $one) {
            if (Carbon::createFromTimestamp($one["timestamp"]) > $timestamp) {
                $telnums_list[] = $one;
            }
        }

        if (empty($name)) {
            $params = [
                "company_id" => $company_id
            ];

            $client = new YclientsService($params);

            $data = $client->getClientNameByTelnum($caller_number);

            if (is_array($data) && count($data) && array_key_exists("name", $data[0])) {
                $name = $data[0]["name"];
            }
        }

        $telnums_list[$caller_number] = [
            "name"      => $name,
            "timestamp" => Carbon::now()->timestamp
        ];

        Cache::put(self::CACHE_YCLIENTS_KEY, $telnums_list, Carbon::now()->addMinutes(30));

        return $name;
    }

    public function getStatisticsForPastDay() {
        try {
            $start_date = Carbon::today()->format('d.m.Y H:i:s');
            $end_date = Carbon::tomorrow()->format('d.m.Y H:i:s');
            $params = [
                "start_date" => $start_date,
                "end_date"   => $end_date,
                "limit"      => 5000,
            ];

            $data = [];

            $service = new MangoService($params);

            $data = $service->get();

            if (array_key_exists("error", $data)) return $data;

            $result = [];

            if (count($data) !== 0) {
                $table = [];
                $telnumsList = self::getTelnumsList();

                foreach ($telnumsList as $telnum => $value) {
                    $table[$telnum] = [
                        "total"      => 0,
                        "missed"     => 0,
                        "received"   => 0,
                        "name"       => $value["name"],
                        "tg_chat_id" => $value["tg_chat_id"],
                        "isActive"   => array_key_exists("tg_active", $value) ? $value["tg_active"] : false
                    ];
                }

                foreach ($data[0]["list"] as $one) {
                    $called_number = $one["called_number"];

                    if (!array_key_exists($called_number, $table)) {
                        continue;
                    }

                    if ($one["context_status"] === 0) {
                        $table[$called_number]["missed"] += 1;
                    } else {
                        $table[$called_number]["received"] += 1;
                    }
                    $table[$called_number]["total"] += 1;
                }

                foreach ($table as $value) {
                    $result[$value['tg_chat_id']][] = [
                        "missed"   => $value["missed"],
                        "received" => $value["received"],
                        "total"    => $value["total"],
                        "name"     => $value["name"],
                        "isActive" => $value["isActive"],
                    ];
                }
            }

            return $result;

        } catch (Throwable $e) {
            ReportService::send("[MANGO] getStatisticsForPastDay", $e->getMessage());

            return [];
        }
    }

    public static function updateBlacklist() {
        try {
            $service = new MangoService();

            $data = $service->blacklist();

            if (array_key_exists("error", $data)) return $data;

            foreach ($data["black"]["numbers"] as $one) {
                $tmp_table = [
                    "number_id"   => $one["number_id"],
                    "number"      => $one["number"],
                    "number_type" => $one["number_type"],
                    "comment"     => $one["comment"],
                ];

                MangoBlacklist::addRecord($tmp_table);
            }

            return json_encode(["info" => "Список обновлен"]);

        } catch (Throwable $e) {
            ReportService::send("[MANGO] blacklist", $e->getMessage());

            return [];
        }
    }

    public function findNumber($search, $list) {
        // TODO: Сейчас обрататывается только крайний случай патерна, где * только в правой части номера
        foreach ($list as $one) {
            list($number) = explode("*", $one["number"]);
            if (str_starts_with($search, $number)) {
                return $number;
            }
        }
        return false;
    }

    public function checkNumberInBlacklist($search) {
        $list = MangoBlacklist::where("is_disabled", 0)
            ->get()
            ->toArray();

        return self::findNumber($search, $list);
    }

    public function filterBlacklist($list): array {
        $data = [];
        if (!is_array($list) || count($list) == 0) return $data;

        $blacklist = MangoBlacklist::where("is_disabled", 0)
            ->get()
            ->toArray();

        foreach($list as $one) {
            if (!self::findNumber($one["caller_number"], $blacklist)) {
                $data[] = $one;
            }
        }

        return $data;
    }

    public function test(): bool {
        $partnerName = env('PARTNER_NAME', '');

        $data = [
            "name"               => "Тестовый филиал - {$partnerName}",
            "client_name"        => "Иван",
            "caller_number"      => "79991234567",
            "called_number"      => "79997654321",
            "context_start_time" => "Прямо сейчас",
            "call_duration"      => "0",
            "tg_chat_id"         => "-1001993054003",
            "isActive"           => true
        ];

        Cache::put(self::TEST_CALL_KEY, $data, Carbon::now()->addMinutes(30));

        return true;
    }
}
