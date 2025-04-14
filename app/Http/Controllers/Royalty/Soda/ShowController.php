<?php

namespace App\Http\Controllers\Royalty\Soda;

use App\Http\Controllers\Controller;
use App\Http\Services\YclientsService;
use App\Models\Partner;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Throwable;

class ShowController extends Controller {

    public function __invoke(Request $request) {
        // Дата окончания (передается из формы)
        $end_date = $request->input("month");

        // Дата начала
        $start_date =  Utils::setFirstDay($end_date);

        $cacheKey = "soda_royalty"."_".$end_date;

        $nds = 5; // НДС 5%

        try {
            $params = [
                "start_date" => $start_date,
                "end_date" => $end_date,
                "company_id" => null
            ];

            $client = new YclientsService($params);

            $partners = Partner::select("name", "yclients_id")
                ->where('yclients_id', '<>', "")
                ->where('disabled', '<>', 1)
                ->orderBy("name")
                ->get();

            $table = Cache::has($cacheKey)
                ? Cache::get($cacheKey)
                : [];

            if(!$table || count($table) == 0) {
                $total_sum = 0;
                $total_income = 0;
                $total_percentage_of_sum = 0;
                $total_sum_with_nds = 0;

                foreach ($partners as $partner) {
                    // Пропускаем партнеров которые начинаются с 000
                    if (str_starts_with($partner->yclients_id, Partner::IGNORE_START_YCLIENTS_ID)) {
                        continue;
                    }

                    $client->setCompanyId($partner->yclients_id);
                    $companyStats = $client->getCompanyStats();

                    if (is_array($companyStats) && array_key_exists("income_total", $companyStats)) {
                        $sum = self::getPercentageOfSum($companyStats["income_total"]);

                        $percentageOfSum = ($sum / 100) * $nds;
                        $sumWithNds = $sum + $percentageOfSum;

                        $table[] = [
                            "name"         => $partner->name,
                            "income_total" => Utils::toPriceFormat($companyStats["income_total"]),
                            "sum"          => Utils::toPriceFormat($sum),
                            "nds"          => Utils::toPriceFormat($percentageOfSum),
                            "sum_with_nds" => Utils::toPriceFormat($sumWithNds),
                        ];

                        $total_income += $companyStats["income_total"];
                        $total_sum += $sum;
                        $total_percentage_of_sum += $percentageOfSum;
                        $total_sum_with_nds += $sumWithNds;

                    } else {
                        $table[] = [
                            "name" => $partner->name,
                            "income_total" => "-",
                            "sum"          => "-",
                            "nds"          => "-",
                            "sum_with_nds" => "-"
                        ];
                    }
                }

                $table[] = [
                    "name"         => "",
                    "income_total" => Utils::toPriceFormat($total_income),
                    "sum"          => Utils::toPriceFormat($total_sum),
                    "nds"          => Utils::toPriceFormat($total_percentage_of_sum),
                    "sum_with_nds" => Utils::toPriceFormat($total_sum_with_nds),
                ];

                Cache::put($cacheKey, $table, Carbon::now()->addMinutes(60));
            }

            $months = Utils::getMonthArray();

            $selected_month = $end_date;

            return view("royalty.soda.show", compact(
                "months",
                "selected_month",
                "table",
            ));

        } catch (Throwable $e) {
            dd($e);
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
