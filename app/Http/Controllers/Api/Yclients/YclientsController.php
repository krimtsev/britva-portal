<?php

namespace App\Http\Controllers\Api\Yclients;

use App\Http\Controllers\Controller;
use App\Http\Services\YclientsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Throwable;

class YclientsController extends Controller
{
    function getVisitsMonthAgo() {

        $date = Carbon::create("2024-02-23")->subDay(30)->format('Y-m-d');

        $params = [
            "start_date" => $date,
            "end_date"   => $date,
            "company_id" => 41120
        ];

        $client = new YclientsService($params);

        $records = $client->getRecordsList();

        if (!is_array($records)) return [
            "error" => "Ошибка получения списка записей"
        ];

        $recordsIds = [];
        foreach ($records as $record) {
            $recordsIds[] = $record["id"];
        }

        $visits = $client->getVisitedForPeriod($recordsIds);

        $result = [];

        foreach ($visits as $visit) {
            if (!array_key_exists("last_visit_date", $visit) ||
                Carbon::parse($visit["last_visit_date"])->diffInDays($date, false) < 0
            ) {
                continue;
            }

            $id = $visit["id"];

            $result[] = [
                "id"       => $id,
                "name"     => $visit["name"],
                "phone"    => $visit["phone"],
                "services" => array_key_exists($id, $records)
                    ? $records[$id]["services"]
                    : [],
                "last_visit_date" => $visit["last_visit_date"],
                "day"      => Carbon::parse($visit["last_visit_date"])->diffInDays($date, false)
            ];
        }

        return $result;
    }
}
