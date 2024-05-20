<?php

namespace App\Http\Controllers\Royalty;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Throwable;
use App\Utils\Utils;
use Illuminate\Http\Request;

class IndexController extends Controller {

    public function __invoke(Request $request) {
        try {
            $months = Utils::getMonthArray();

            $selected_month = array_keys($months)[0];

            $partners = Partner::select("name", "yclients_id")
                ->where('yclients_id', '<>', "")
                ->where('disabled', '<>', 1)
                ->orderBy("name")
                ->get();

            $selected_partner = $partners[0]->yclients_id;

            return view("royalty.index", compact(
                "months",
                "selected_month",
                "partners",
                "selected_partner",
            ));

        } catch (Throwable $e) {
            report($e->getMessage());

            return [];
        }
    }

}
