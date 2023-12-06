<?php

namespace App\Http\Controllers\Jobs;

use Illuminate\Http\Request;
use App\Jobs\GenerateAnalyticsJob;
use App\Models\User;
use App\Utils\Utils;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $selected_month = Utils::getPreviousStartMonth();

        $date =  Utils::getPeriodMonthArray($selected_month, 1)[0];

        $users = User::select("name", "yclients_id")->where('yclients_id', '<>', "")->orderBy("name")->get();

        foreach ($users as $user) {
            GenerateAnalyticsJob::dispatch(
                false,
                $date["start_date"],
                $date["end_date"],
                $user->yclients_id
            )->onConnection('database')->onQueue("analytics");
        }

        return view('dashboard.jobs.start');
    }

}
