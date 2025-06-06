<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use App\Http\Services\YclientsService;
use App\Jobs\StaffJob;
use App\Models\Partner;
use App\Models\Staff;
use Illuminate\Http\Request;
use Throwable;

class StaffController extends Controller
{
    /**
     * Устанавливаем задачи (Queues) для каждого парнера
     * setJobs -> StaffJob -> update
     */
    static function setJobs() {
        $partners = Partner::available();

        try {
            foreach ($partners as $partner) {
                StaffJob::dispatch($partner->yclients_id);
            }

            return json_encode(["info" => "Задачи обновления сотрудников поставлены"]);

        } catch (Throwable $e) {
            ReportService::error("[StaffController] setJobs", $e->getMessage());

            return [];
        }
    }

    /*
     * Обновить информацию по сотрудникам
     * quiet - Обновить базу без уволеных, отправка уведомлений отключена.
     */
    public function sync(Request $request)
    {
        $quiet = filter_var($request->input("quiet"), FILTER_VALIDATE_BOOLEAN);

        $partners = Partner::available();

        foreach ($partners as $partner) {
            self::update($partner->yclients_id, $quiet);
        }
    }

    /**
     * @param $company_id
     * @param bool $quiet
     * @return bool
     */
    static function update($company_id, bool $quiet = false): bool
    {
        $client = new YclientsService([
            "company_id" => $company_id,
        ]);

        // Список сотрудников
        $staff = $client->getStaff(['withRemoveStaff' => true]);

        if (!is_array($staff)) {
            ReportService::error("[Staff] update", "bad request, partner: {$company_id}");
            return false;
        }

        if (count($staff) == 0) {
            ReportService::error("[Staff] update", "staff is empty, partner: {$company_id}");
            return false;
        }

        $_staff = Staff::whereIn("staff_id", array_keys($staff))
            ->get()
            ->toArray();

        $staff_old = [];

        foreach ($_staff as $one) {
            $id = $one["id"] = (string)$one["staff_id"];
            $staff_old[$id] = $one;
        }

        foreach ($staff as $id => $one) {
            $data_new = self::prepareData($one, $company_id);
            $data_old = [];

            if (array_key_exists($id, $staff_old)) {
                $data_old = self::prepareData($staff_old[$id], $company_id);
            }

            $data_diff = array_diff($data_new, $data_old);

            if (!empty($data_diff)) {
                Staff::addRecord($data_new);

                StaffAuditController::handler($data_new, $company_id);

                if (!$quiet) {
                    StaffAuditController::sendMessage($company_id, $one, $data_new, $data_old);
                }
            }
        }

        return true;
    }

    /**
     * Нормализация данных для записи в базу и сравнения
     * @param $data
     * @param $company_id
     * @return array
     */
    static function prepareData($data, $company_id = null): array
    {
        $company_id = array_key_exists("company_id", $data)
            ? $data["company_id"]
            : $company_id;

        return [
            "staff_id"       => $data["id"],
            "company_id"     => $company_id,
            "name"           => $data["name"],
            "specialization" => $data["specialization"],

            // Обязательное преобразование для исправления сапоставления типов!
            "is_fired"       => filter_var($data["is_fired"], FILTER_VALIDATE_BOOLEAN),
        ];
    }
}
