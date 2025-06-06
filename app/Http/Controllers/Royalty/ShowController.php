<?php

namespace App\Http\Controllers\Royalty;

use App\Http\Controllers\Controller;
use App\Http\Services\YclientsService;
use App\Models\Partner;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class ShowController extends Controller {

    public function __invoke(Request $request) {
        // Дата окончания (передается из формы)
        $end_date = $request->input("month");

        // Дата начала
        $start_date =  Utils::setFirstDay($end_date);

        // ID филиала
        $company_id = $request->input("company_id");

        // Если ID филиала не указан, берем его у авторизированного пользователя
        if (!$company_id) {
            $partner_id = Auth::user()->partner_id;
            $partner = Partner::select("yclients_id")->where('id', $partner_id)->first();
            if ($partner->yclients_id) {
                $company_id = $partner->yclients_id;
            } else {
                $company_id_not_found = true;

                // возвращаем ошибку если ID партнера не найден
                return view("royalty.show", compact("company_id_not_found"));
            }
        }

        try {
            $params = [
                "start_date" => $start_date,
                "end_date" => $end_date,
                "company_id" => $company_id
            ];

            $client = new YclientsService($params);

            $staff = $client->getStaff(["withRemoveStaff" => true]);

            $table = [];

            foreach ($staff as $one) {
                $id = $one["id"];

                if(!Str::of(Str::lower($one["specialization"]))->contains(['барбер', 'эксперт', 'barber', 'expert'])) {
                    continue;
                }

                $royaltyData = $client->getRoyaltyByStaffId($id);

                if ($royaltyData) {
                    $table[$id]["data"] =  $royaltyData;
                } else {
                    continue;
                }

                $table[$id]["name"] = $one["name"];
                $table[$id]["specialization"] = $one["specialization"];
            }

            $months = Utils::getMonthArray();

            $daysList = array_reverse(Utils::getListDaysByPeriod($start_date, $end_date));

            $partners = Partner::select("name", "yclients_id")
                ->where('yclients_id', '<>', "")
                ->where('disabled', '<>', 1)
                ->orderBy("name")
                ->get();

            $selected_month = $end_date;

            $selected_partner = $company_id;

            $total = [];

            foreach ($daysList as $date => $day) {
                foreach ($table as $selectedStaff) {
                    if (!array_key_exists($date, $total)) {
                        $total[$date] = 0;
                    }

                    if (count($selectedStaff["data"]) == 0) {
                        continue;
                    }

                    foreach ($selectedStaff["data"] as $staff_date => $staff_day) {
                        if($date == $staff_date) {
                            $total[$date] += 1;
                        }
                    }
                }
            }

            return view("royalty.show", compact(
                "months",
                "selected_month",
                "partners",
                "selected_partner",
                "daysList",
                "table",
                "total"
            ));

        } catch (Throwable $e) {
            report($e->getMessage());

            return [];
        }
    }

}
