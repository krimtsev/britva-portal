<?php

namespace App\Http\Controllers\Api\Mango;

use App\Http\Services\MangoService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class MangoController extends Controller
{
    protected $cacheKey = "mango_last_telnums_call";

    protected $whiteListTelnums = [
        "74994606679" => "Авиамоторная",
        "74991101276" => "Академическая",
        "74994502602" => "Балашиха",
        "74994442194" => "Бульвар Рокоссовского",
        "74991107612" => "Измайловская",
        "74994551025" => "Люберцы",
        "74991107696" => "Новокосино (Октября 20)",
        "74991108260" => "Новокосино (Новокосинская 8)",
        "74994556805" => "Партизанская",
        "74991105455" => "Первомайская",
        "74994502902" => "Семеновская",
        "74993022031" => "Технопарк",
        "74994508616" => "Шелепиха"
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
                    "name"               => $this->whiteListTelnums[$called_number],
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
        $cached_list = Cache::get($this->cacheKey, []);

        $telnums_list = [];
        $ids = [];

        foreach ($cached_list as $one) {
            if (Carbon::createFromTimestamp($one["timestamp"]) >  $start_date) {
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
}
