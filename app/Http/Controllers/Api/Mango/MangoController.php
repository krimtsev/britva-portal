<?php

namespace App\Http\Controllers\Api\Mango;

use App\Http\Services\MangoService;
use App\Http\Controllers\Controller;
use App\Http\Services\YclientsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Throwable;

class MangoController extends Controller
{
    protected $cacheKey = "mango_last_telnums_call";
    protected $cacheNamesKey = "yclients_last_names_call";

    public function index(Request $request)
    {
        if ($request->input("flush")) {
            Cache::flush();
        }

        $date = Carbon::now();
        $end_date = $date->format('d.m.Y H:i:s');
        $start_date = $date->subMinutes(30)->format('d.m.Y H:i:s');

        $data = [];
        try {
            $service = new MangoService($start_date, $end_date);

            $data = $service->get();
        } catch (Throwable $e) {
            report("[MANGO] [ERROR] get: " . $e);
        }

        if (array_key_exists("error", $data)) return $data;

        $table = [];

        list($cachedTelnumsList, $ids) = self::getCachedTelnums($start_date);

        $whiteListTelnums = $this->getWhiteTelnumsList();

        if (count($data) !== 0) {
            foreach ($data[0]["list"] as $one) {
                $called_number = $one["called_number"];

                if (!array_key_exists($called_number, $whiteListTelnums)) {
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
                        $whiteListTelnums[$called_number]["company_id"],
                        $one["caller_number"],
                        $start_date
                    );
                } catch (Throwable $e) {
                    report("[YCLIENTS] [ERROR] getClientName: " . $e);
                }

                $table[] = [
                    "name"               => $whiteListTelnums[$called_number]["name"],
                    "client_name"        => $client_name,
                    "caller_number"      => $one["caller_number"],
                    "called_number"      => $called_number,
                    "context_start_time" => Carbon::createFromTimestamp($one["context_start_time"])->format('d.m.Y H:i:s'),
                    "call_duration"      => $one["duration"],
                    "tg_chat_id"         => $whiteListTelnums[$called_number]["tg_chat_id"]
                ];
            }
        }

        Cache::put($this->cacheKey, $cachedTelnumsList, Carbon::now()->addMinutes(30));

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

    protected function getWhiteTelnumsList() {
		// try {
			
		// } catch (Throwable $e) {
			// report("[YCLIENTS] [ERROR] getClientName: " . $e);
        // }
		
		
        $list = storage_path('mango/britva/telnums.json');
		
        if (is_string($list)) {
            if (!file_exists($list)) {
                throw new Error(sprintf('file "%s" does not exist', $list));
            }

            $json = file_get_contents($list);
			
					// dd(json_decode($json, true));

            if (!$list = json_decode($json, true)) {
                throw new Error('invalid json for auth config');
            }

            return $list;
        }

        return [];
    }
}
