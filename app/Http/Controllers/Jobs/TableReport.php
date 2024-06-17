<?php

namespace App\Http\Controllers\Jobs;

use App\Models\Partner;
use Illuminate\Http\Request;
use App\Jobs\AnalyticsJob;
use App\Utils\Utils;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Throwable;
use App\Config\Constants;

class TableReport extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $selected_month = Utils::getPreviousStartMonth();
            $date =  Utils::getPeriodMonthArray($selected_month, 1)[0];

            if($request->input("period")) {
                $start = Carbon::createFromFormat("Y-m-d", $request->input("period"))->startOfMonth();
                $end = Carbon::createFromFormat("Y-m-d", $request->input("period"))->endOfMonth();

                $prevPerion = Carbon::createFromFormat("Y-m-d", $selected_month)->startOfMonth();
                $startPeriod = Carbon::createFromFormat("Y-m-d", Constants::START_DATE)->startOfMonth();

                if($start > $prevPerion) {
                    return [
                        "start" => $start,
                        "prevPerion" => $prevPerion
                    ];
                }

                if($start < $startPeriod) {
                    return [
                        "start" => $start,
                        "startPeriod" => $startPeriod
                    ];
                }

                $date = [
                    "start_date" => $start->format("Y-m-d"),
                    "end_date" => $end->format("Y-m-d")
                ];
            }

        } catch (Throwable $e) {
            dd($e);
        }

        $partners = Partner::select("name", "yclients_id")
            ->where('yclients_id', '<>', "")
            ->where('disabled', '<>', 1)
            ->orderBy("name")
            ->get();

        foreach ($partners as $partner) {
            AnalyticsJob::dispatch(
                false,
                $date["start_date"],
                $date["end_date"],
                $partner->yclients_id
            )->onConnection('database')->onQueue("analytics");
        }

        return view('dashboard.jobs.start', compact("date"));
    }

}
