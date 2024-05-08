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
    private function getClientsVisits($clients_key)
    {
        try {
            $client = new YclientsService();

            $partners = Telnums::getTelnumsList();

            $result = [];

            foreach ($partners as $key => $partner) {
                if (!array_key_exists($clients_key, $partner) ||
                    !is_numeric($partner[$clients_key]) ||
                    $partner[$clients_key] <= 0
                ) continue;

                $client_days = $partner[$clients_key];

                $date = Carbon::now()->subDay($client_days)->format('Y-m-d');

                $client->updateParams([
                    "company_id" => $partner["company_id"],
                    "start_date" => $date,
                    "end_date"   => $date,
                ]);

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
                    if (!array_key_exists("last_visit_date", $visit) &&
                        Carbon::parse($visit["last_visit_date"])->diffInDays($date, false) < 0 &&
                        ($clients_key === "new_client_days" && $visit["visits_count"] != 1)
                    ) continue;

                    $id = $visit["id"];

                    $tg_chat_id = $partner["tg_chat_id"];
                    $company_id = $partner["company_id"];

                    $result[$tg_chat_id][$company_id][] = [
                        "id"           => $id,
                        "name"         => $visit["name"],
                        "phone"        => $visit["phone"],
                        "visits_count" => $visit["visits_count"],
                        "services"     => array_key_exists($id, $records)
                            ? $records[$id]["services"]
                            : [],
                        "partner_name" => $partner["name"],
                        "days"         => $client_days
                    ];
                }
            }

            return $result;
        } catch (Throwable $e) {
            report($e->getMessage());
            return false;
        }
    }

    public function getLostClients() {
        return $this->getClientsVisits("lost_client_days");
    }

    public function getNewClients() {
        return $this->getClientsVisits("new_client_days");
    }
}
