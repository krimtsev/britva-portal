<?php

namespace App\Http\Controllers\Api\Yclients;

use App\Http\Controllers\Controller;
use App\Http\Services\YclientsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Throwable;
use App\Utils\Telnums;

class YclientsController extends Controller
{
    function getVisitsMonthAgo() {
        try {
            $date = Carbon::now()->subDay(30)->format('Y-m-d');

            $params = [
                "start_date" => $date,
                "end_date"   => $date,
            ];

            $client = new YclientsService($params);

            $partners = Telnums::getTelnumsList();

            $result = [];

            foreach ($partners as $key => $partner) {
                if (!array_key_exists("send_visits", $partner) || $partner["send_visits"] !== true) continue;

                $client->setCompanyId($partner["company_id"]);

                $records = $client->getRecordsList();

                if (!is_array($records)) return [
                    "error" => "Ошибка получения списка записей"
                ];

                $recordsIds = [];

                foreach ($records as $record) {
                    $recordsIds[] = $record["id"];
                }

                $visits = $client->getVisitedForPeriod($recordsIds);

                foreach ($visits as $visit) {
                    if (!array_key_exists("last_visit_date", $visit) ||
                        Carbon::parse($visit["last_visit_date"])->diffInDays($date, false) < 0
                    ) {
                        continue;
                    }

                    $id = $visit["id"];

                    $tg_chat_id = $partner["tg_chat_id"];
                    $company_id = $partner["company_id"];

                    $result[$tg_chat_id][$company_id][] = [
                        "id"              => $id,
                        "name"            => $visit["name"],
                        "phone"           => $visit["phone"],
                        "visits_count"    => $visit["visits_count"],
                        "services"        => array_key_exists($id, $records)
                            ? $records[$id]["services"]
                            : [],
                        "partner_name"    => $partner["name"]
                    ];
                }

            }

            return $result;

        } catch (Throwable $e) {
            report($e->getMessage());
            return false;
        }
    }
}
