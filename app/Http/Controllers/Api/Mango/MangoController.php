<?php

namespace App\Http\Controllers\Api\Mango;

use App\Http\Services\MangoService;
use App\Http\Controllers\Controller;
use App\Http\Services\YclientsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Throwable;
use App\Utils\Telnums;

class MangoController extends Controller
{
    protected $cacheMangoKey = "mango_last_telnums_call";
    protected $cacheYclientsKey = "yclients_last_names_call";

    protected $testCallKey = "mango_test_call";

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
            report("[MANGO] [ERROR] get: " . $e->getMessage());
        }

        if (array_key_exists("error", $data)) return $data;

        $table = [];

        list($cachedTelnumsList, $ids) = self::getCachedTelnums($start_date);

        $telnumsList = Telnums::getTelnumsList();

        if (count($data) !== 0) {
            foreach ($data[0]["list"] as $one) {
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
                        $telnumsList[$called_number]["company_id"],
                        $one["caller_number"],
                        $start_date
                    );
                } catch (Throwable $e) {
                    report("[YCLIENTS] [ERROR] getClientName: " . $e->getMessage());
                }

                $table[] = [
                    "name"               => $telnumsList[$called_number]["name"],
                    "client_name"        => $client_name,
                    "caller_number"      => $one["caller_number"],
                    "called_number"      => $called_number,
                    "context_start_time" => Carbon::createFromTimestamp($one["context_start_time"])->format('d.m.Y H:i:s'),
                    "call_duration"      => $one["duration"],
                    "tg_chat_id"         => $telnumsList[$called_number]["tg_chat_id"],
                    "isActive"           => array_key_exists("active", $telnumsList[$called_number])
                ];
            }
        }

        Cache::put($this->cacheMangoKey, $cachedTelnumsList, Carbon::now()->addMinutes(40));

        /*
         * Добавляем тестовый звонок
         */
        if(Cache::has($this->testCallKey)) {
            $table[] = Cache::get($this->testCallKey);
            Cache::forget($this->testCallKey);
        }

        return $table;
    }

    protected function getCachedTelnums($start_date): array
    {
        $timestamp = Carbon::parse($start_date);
        $cached_list = Cache::get($this->cacheMangoKey, []);

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

    protected function createID($timestamp, $called_number): string
    {
        return "{$timestamp}_{$called_number}";
    }

    protected function getClientName($company_id, $caller_number, $start_date) {
        $timestamp = Carbon::parse($start_date);
        $cached_list = Cache::get($this->cacheYclientsKey, []);
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

        Cache::put($this->cacheYclientsKey, $telnums_list, Carbon::now()->addMinutes(30));

        return $name;
    }

    public function getStatisticsForPastDay() {
        $start_date = Carbon::today()->format('d.m.Y H:i:s');
        $end_date = Carbon::tomorrow()->format('d.m.Y H:i:s');
        $params = [
            "start_date" => $start_date,
            "end_date"   => $end_date,
            "limit"      => 5000,
        ];

        $data = [];
        try {
            $service = new MangoService($params);

            $data = $service->get();

        } catch (Throwable $e) {
            report("[MANGO] [ERROR] getStatisticsForPastDay: " . $e->getMessage());
        }

        if (array_key_exists("error", $data)) return $data;

        $result = [];

        if (count($data) !== 0) {
            $table = [];
            $telnumsList = Telnums::getTelnumsList();

            foreach ($telnumsList as $telnum => $value) {
                $table[$telnum] = [
                    "total"      => 0,
                    "missed"     => 0,
                    "received"   => 0,
                    "name"       => $value["name"],
                    "tg_chat_id" => $value["tg_chat_id"],
                    "isActive"   => array_key_exists("active", $value) ? $value["active"] : false
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
    }

    public function test() {
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

        Cache::put($this->testCallKey, $data, Carbon::now()->addMinutes(30));

        return true;
    }
}
