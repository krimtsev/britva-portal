<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use App\Models\Audit;
use App\Models\Partner;

class StaffAuditController extends Controller
{
    static function handler($data_new, $data_old) {
        Audit::add([
            "type" => Audit::$types['staff'],
            "new"  => $data_new,
            "old"  => $data_old,
        ]);
    }

    static function sendMessage($company_id, $staff, $data_new, $data_old) {
        $msg = "Изменены данные сотрудника";

        $partner = Partner::select("name")
            ->where("yclients_id", $company_id)
            ->first();

        $data = [];
        $data["new"] = $data_new;
        $data["old"] = $data_old;
        $data["company_name"] = $partner["name"];

        // Доп. данные если добавлен новый сотрудник
        if (empty($data_old)) {
            $data["avatar"] = $staff["avatar_big"];
            $data["isNew"] = true;
        }

        // Номер телефона не логируем и не проверяем изменения
        // Отправляем текущий телефон для уведомления
        $data["phone"] = $staff["phone"];

        ReportService::msg(Audit::$types['staff'], $msg, $data);
    }
}
