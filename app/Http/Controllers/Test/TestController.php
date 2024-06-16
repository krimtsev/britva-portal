<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Utils\Telnums;
use Throwable;
use App\Utils\Utils;
use Illuminate\Http\Request;

class TestController extends Controller {

    public function updateTelnums() {
        $partners = Telnums::getTelnumsList();

        foreach ($partners as $key => $partner) {
            Partner::where('mango_telnum', $key)->update([
                "repeat_client_days" => $partner["repeat_client_days"],
                "new_client_days" => $partner["new_client_days"],
                "tg_active" => $partner["active"],
                "tg_chat_id" => $partner["tg_chat_id"],
            ]);
        }
    }
}
