<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use App\Http\Services\YclientsService;
use App\Models\Partner;
use Carbon\Carbon;
use Throwable;

class ClientsVisitsController extends Controller
{
    const NEW_CLIENTS = "new_client_days";
    const LOST_CLIENTS = "lost_client_days";
    const REPEAT_CLIENTS = "repeat_client_days";

    private function getClientsVisits($clients_key)
    {
        try {
            $client = new YclientsService();

            $partners = Partner::select(
                "name",
                "yclients_id",
                "mango_telnum",
                "tg_chat_id",
                "lost_client_days",
                "repeat_client_days",
                "new_client_days"
            )
                ->where('yclients_id', '<>', "")
                ->where('disabled', '<>', 1)
                ->where("tg_active", 1)
                ->get();

            $result = [];

            foreach ($partners as $partner) {
                if ($partner[$clients_key] <= 0) continue;

                $client_days = $partner[$clients_key];

                $date = Carbon::now()->subDay($client_days)->format('Y-m-d');

                $client->updateParams([
                    "company_id" => $partner->yclients_id,
                    "start_date" => $date,
                    "end_date"   => $date,
                ]);

                $records = $client->getRecordsList();

                if (!is_array($records)) {
                    ReportService::send("getClientsVisits {$clients_key} not found records", $partner->yclients_id);
                    continue;
                }

                $recordsIds = [];

                foreach ($records as $record) {
                    $recordsIds[] = $record["id"];
                }

                $visits = $client->getVisitedForPeriod($recordsIds);

                if (!is_array($visits)) {
                    ReportService::send("getClientsVisits {$clients_key} not found visits", $partner->yclients_id);
                    continue;
                }

                foreach ($visits as $visit) {
                    if (!array_key_exists("last_visit_date", $visit) ||
                        Carbon::parse($visit["last_visit_date"])->diffInDays($date, false) < 0 ||
                        (($clients_key === self::NEW_CLIENTS) && $visit["visits_count"] != 1)
                    ) continue;

                    $id = $visit["id"];

                    $tg_chat_id = $partner->tg_chat_id;
                    $company_id = $partner->yclients_id;

                    $result[$tg_chat_id][$company_id][] = [
                        "id"            => $id,
                        "name"          => $visit["name"],
                        "phone"         => $visit["phone"],
                        "visits_count"  => $visit["visits_count"],
                        "services"      => array_key_exists($id, $records)
                            ? $records[$id]["services"]
                            : [],
                        "partner_name"  => $partner->name,
                        "days"          => $client_days,
                        "selected_date" => $date,
                        "company_id"    => $company_id
                    ];
                }
            }

            return $result;
        } catch (Throwable $e) {
            ReportService::send("getClientsVisits {$clients_key}", $e->getMessage());
            return false;
        }
    }

    public function getLostClients() {
        return $this->getClientsVisits(self::LOST_CLIENTS);
    }

    public function getNewClients() {
        return $this->getClientsVisits(self::NEW_CLIENTS);
    }

    public function getRepeatClients() {
        return $this->getClientsVisits(self::REPEAT_CLIENTS);
    }
}
