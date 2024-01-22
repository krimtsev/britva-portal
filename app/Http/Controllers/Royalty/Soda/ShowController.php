<?php

namespace App\Http\Controllers\Royalty\Soda;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Analytics\Throwable;
use App\Http\Services\YclientsService;
use App\Models\User;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ShowController extends Controller {

    public function __invoke(Request $request) {
        // Дата окончания (передается из формы)
        $end_date = $request->input("month");

        // Дата начала
        $start_date =  Utils::setFirstDay($end_date);

        $cacheKey = "soda_royalty"."_".$end_date;

        try {
            $params = [
                "start_date" => $start_date,
                "end_date" => $end_date,
                "company_id" => null
            ];

            $client = new YclientsService($params);

            $users = User::select("name", "yclients_id")->where('yclients_id', '<>', "")->orderBy("name")->get();

            $table = [];

            if(Cache::has($cacheKey)) {
                $table = Cache::get($cacheKey);
            } else {
                foreach ($users as $user) {
                    $client->updateCompanyId($user->yclients_id);
                    $companyStats = $client->getCompanyStats();

                    $table[] = [
                        "name" => $user->name,
                        "income_total" => number_format($companyStats["income_total"], 2, ',', ' '),
                        "sum" => number_format(self::getPercentageOfSum($companyStats["income_total"]), 2, ',', ' ')
                    ];
                }

                Cache::put($cacheKey, $table, Carbon::now()->addMinutes(5));
            }

            $months = Utils::getMonthArray();

            $selected_month = $end_date;

            return view("royalty.soda.show", compact(
                "months",
                "selected_month",
                "table",
            ));

        } catch (Throwable $e) {
            report($e);

            return [];
        }
    }

    public function getPercentageOfSum($sum) {
        if (!is_float($sum)) {
            $sum = (float)$sum;
        }

        if($sum < 2000000) {
            return $sum * (3 / 100);
        }

        return $sum * (2.5 / 100);
    }

}
