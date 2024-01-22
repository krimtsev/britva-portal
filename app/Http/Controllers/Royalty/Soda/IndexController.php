<?php

namespace App\Http\Controllers\Royalty\Soda;

use App\Http\Controllers\Controller;
use Throwable;
use App\Utils\Utils;
use Illuminate\Http\Request;

class IndexController extends Controller {

    public function __invoke(Request $request) {
        try {
            $months = Utils::getMonthArray();

            $selected_month = array_keys($months)[0];

            return view("royalty.soda.index", compact(
                "months",
                "selected_month",
            ));

        } catch (Throwable $e) {
            report($e);

            return [];
        }
    }

}
