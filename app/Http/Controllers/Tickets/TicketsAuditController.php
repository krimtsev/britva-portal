<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use App\Models\Audit;
use App\Models\Partner;
use App\Models\Ticket\TicketCategory;

class TicketsAuditController extends Controller
{
    static function handler($data_new, $data_old, $data_new_c = [], $data_old_c = []) {
        Audit::add([
            "type" => Audit::$types['ticket'],
            "new"  => array_merge($data_new, $data_new_c),
            "old"  => array_merge($data_old, $data_old_c),
        ]);
    }

    static function sendMessage($ticket, $info, $user = null) {
        switch ($ticket['state']) {
            case 1:
                $state = "Создана новая заявка";
                break;
            case 2:
                $state = "Заявка принята в работу";
                break;
            case 3:
                $state = "Заявка переведена в статус ожидает";
                break;
            case 4:
                $state = "Заявка переведена в статус решено";
                break;
            case 5:
                $state = "Заявка переведена в статус закрыто";
                break;
            case 6:
                $state = "Заявка переведена в статус октлонено";
                break;
            default:
                $state = "Статус не определен";
        }

        $partner_id = $ticket["partner_id"];
        $category_id = $ticket["category_id"];

        $partner = Partner::select("name")
            ->where("id", $partner_id)
            ->first();

        $category = TicketCategory::select("title")
            ->where("id", $category_id)
            ->first();

        $data = [];
        $data["id"] = $info["id"];
        $data["title"] = $ticket["title"];
        $data["company_name"] = $partner["name"];
        $data["category"] = $category["title"];
        $data["state"] = $state;

        if ($user) {
            $data["login"] = $user["login"];
        }

        ReportService::msg(Audit::$types['ticket'], "", $data);
    }
}
