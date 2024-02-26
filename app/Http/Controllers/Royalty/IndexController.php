<?php

namespace App\Http\Controllers\Royalty;

use App\Http\Controllers\Controller;
use Throwable;
use App\Models\User;
use App\Utils\Utils;
use Illuminate\Http\Request;

class IndexController extends Controller {

    public function __invoke(Request $request) {
        try {
            $months = Utils::getMonthArray();

            $selected_month = array_keys($months)[0];

            $users = User::select("name", "yclients_id")->where('yclients_id', '<>', "")->orderBy("name")->get();
            $selected_user = $users[0]->yclients_id;

            return view("royalty.index", compact(
                "months",
                "selected_month",
                "users",
                "selected_user",
            ));

        } catch (Throwable $e) {
            report($e->getMessage());

            return [];
        }
    }

}
