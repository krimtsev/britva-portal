<?php

namespace App\Http\Controllers\Royalty;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Analytics\Throwable;
use App\Http\Services\YclientsService;
use App\Models\User;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $company_id = Auth::user()->yclients_id;
        }

        try {
            $params = [
                "start_date" => $start_date,
                "end_date" => $end_date,
                "company_id" => $company_id
            ];

            $client = new YclientsService($params);

            $staff = $client->getStaff();

            $table = [];

            foreach ($staff as $one) {
                $id = $one["id"];

                $table[$id]["name"] = $one["name"];

                $royaltyData = $client->getRoyaltyByStaffId($id);

                if ($royaltyData) {
                    $table[$id]["data"] =  $royaltyData;
                } else {
                    $table[$id]["data"] = [];
                }
            }

            $months = Utils::getMonthArray();

            $daysList = array_reverse(Utils::getListDaysByPeriod($start_date, $end_date));

            $users = User::select("login", "name", "yclients_id")->orderBy("name")->get();

            $selected_month = $end_date;

            $selected_user = $company_id;

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
                "users",
                "selected_user",
                "daysList",
                "table",
                "total"
            ));

        } catch (Throwable $e) {
            report($e);

            return [];
        }
    }

}
