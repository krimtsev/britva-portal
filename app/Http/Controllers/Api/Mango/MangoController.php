<?php

namespace App\Http\Controllers\Api\Mango;

use App\Http\Services\MangoService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class MangoController extends Controller
{
    protected $cacheKey = "mango_last_call_timestamp";

    public function index()
    {
        $date = Carbon::now();

        $lastCallTimestamp = \Cache::get($this->cacheKey, false);

        $end_date = $date->format('d.m.Y H:i:s');

        $start_date = $lastCallTimestamp
            ? Carbon::createFromTimestamp($lastCallTimestamp)->addSecond()->format('d.m.Y H:i:s')
            : $date->subMinutes(30)->format('d.m.Y H:i:s');

        $service = new MangoService($start_date, $end_date);

        $data = $service->get();

        if (array_key_exists("error", $data)) return $data;

        $table = [];

        if (count($data) !== 0) {
            foreach ($data[0]["list"] as $one) {
                $table[] = [
                    "timestamp" => $one["context_start_time"],
                    "name" => !empty($one["context_calls"]) ? $one["context_calls"][0]["call_abonent_info"] : "",
                    "caller_number" => $one["caller_number"],
                    "called_number" => $one["called_number"],
                    "context_start_time" => Carbon::createFromTimestamp($one["context_start_time"])->format('d.m.Y H:i:s'),
                    "call_duration" => !empty($one["context_calls"]) ? $one["context_calls"][0]["call_duration"] : ""
                ];
            }

            $lastEl = Arr::first($table);

            Cache::put($this->cacheKey, $lastEl["timestamp"], Carbon::now()->addMinutes(120));
        } else {
            Cache::put($this->cacheKey, $date->getTimestamp(), Carbon::now()->addMinutes(120));
        }

        return $table;
    }

}
