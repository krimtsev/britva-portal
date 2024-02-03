<?php

namespace App\Http\Controllers\Api\Mango;

use App\Http\Services\MangoService;
use App\Http\Controllers\Controller;
use App\Http\Services\YclientsService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class MangoController extends Controller
{
    protected $cacheKey = "mango_last_telnums_call";
    protected $cacheNamesKey = "yclients_last_names_call";

    protected $whiteListTelnums = [
        "74992262771" => [
            "name"       => "Академическая",
            "company_id" => "849036"
        ],
		"74993027014" => [
            "name"       => "Аминьевская (ЖК Событие)",
            "company_id" => "916286"
        ],
		"74993022956" => [
            "name"       => "Бульвар Рокоссовского",
            "company_id" => "868716"
        ],
		"74996538683" => [
            "name"       => "Измайловская",
            "company_id" => "607560"
        ],
		"74994505859" => [
            "name"       => "Митино",
            "company_id" => "500005"
        ],
		"74994509606" => [
            "name"       => "Новокосино",
            "company_id" => "107458"
        ],
		"74994509006" => [
            "name"       => "Первомайская",
            "company_id" => "144156"
        ],
		"74993023057" => [
            "name"       => "Спартак",
            "company_id" => "859934"
        ],
		"74996535956" => [
            "name"       => "Химки (ул. Бабакина)",
            "company_id" => "277427"
        ],
		"74992888715" => [
            "name"       => "Химки (ул. Лавочкина)",
            "company_id" => "876434"
        ],
		"74992881317" => [
            "name"       => "Шелепиха",
            "company_id" => "849037"
        ],
		"74992881301" => [
            "name"       => "Юго-западная",
            "company_id" => "843360"
        ],
    ];

    public function index(Request $request)
    {
        if ($request->input("flush")) {
            Cache::flush();
        }

        $date = Carbon::now();
        $end_date = $date->format('d.m.Y H:i:s');
        $start_date = $date->subMinutes(30)->format('d.m.Y H:i:s');

        $service = new MangoService($start_date, $end_date);

        $data = $service->get();

        if (array_key_exists("error", $data)) return $data;

        $table = [];

        list($telnums_list, $ids) = self::getCachedTelnums($start_date);

        if (count($data) !== 0) {
            foreach ($data[0]["list"] as $one) {
                $called_number = $one["called_number"];

                if (!array_key_exists($called_number, $this->whiteListTelnums)) {
                    continue;
                }

                $timestamp = $one["context_start_time"];
                $id = self::createID($timestamp, $called_number);

                if (in_array($id, $ids)) {
                    continue;
                }

                $telnums_list[] = [
                    "timestamp"     => $timestamp,
                    "called_number" => $called_number
                ];

                $table[] = [
                    "name"               => $this->whiteListTelnums[$called_number]["name"],
                    "client_name"        => $this->getClientName(
                        $this->whiteListTelnums[$called_number]["company_id"],
                        $one["caller_number"],
                        $start_date
                    ),
                    "caller_number"      => $one["caller_number"],
                    "called_number"      => $called_number,
                    "context_start_time" => Carbon::createFromTimestamp($one["context_start_time"])->format('d.m.Y H:i:s'),
                    "call_duration"      => $one["duration"]
                ];
            }

        }

        Cache::put($this->cacheKey, $telnums_list, Carbon::now()->addMinutes(30));

        return $table;
    }

    protected function getCachedTelnums($start_date): array
    {
        $timestamp = Carbon::parse($start_date);
        $cached_list = Cache::get($this->cacheKey, []);

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
        $cached_list = Cache::get($this->cacheNamesKey, []);
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

        Cache::put($this->cacheNamesKey, $telnums_list, Carbon::now()->addMinutes(30));

        return $name;
    }
}
