<?php

namespace App\Http\Controllers\Jobs;

use App\Utils\Telnums;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\ClientsVisitsJob;
use App\Http\Controllers\Analytics\ClientsVisitsController;

class ClientsVisits extends ClientsVisitsController
{
    public function __invoke(Request $request)
    {
        $partners = Telnums::getTelnumsList();

        foreach ([self::NEW_CLIENTS, self::LOST_CLIENTS] as $clients_type) {

            foreach ($partners as $key => $partner) {
                if (!array_key_exists($clients_type, $partner) ||
                    !is_numeric($partner[$clients_type]) ||
                    $partner[$clients_type] <= 0
                ) continue;
            }

            $client_days = $partner[$clients_type];

            $subDay = Carbon::now()->subDay($client_days)->startOfDay()->format('Y-m-d');
            $day = Carbon::now();

            /*
             * 1. Нужно добавить запись в базе
             * 2. Создать задачу на выполнение
             * 3.1. В задаче отметить в базе как выполненную
             * 3.2. В задаче отправить сообщение в телеграм
             */

            ClientsVisitsJob::dispatch(
                $subDay,
                $clients_type,
                $partner["company_id"],
                $partner["tg_chat_id"]
            )->onConnection('database')->onQueue("visits");
        }

    }

}
