<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffController extends Controller
{
    function index(Request $request) {
        $update = json_decode($request, true);

        ReportService::send($request);

        if(!$update) {
            exit;
        }

        if(isset($update["message"])) {
            $message = $update["message"];
            $chatId = $message["chat"]["id"];

            $website = "https://api.telegram.org/bot7318392454:AAF3IdsFqWSrnKv7PiKdsh_jHADeTfwED00";

            $query = http_build_query([
                "chat_id"    => $chatId,
                "text"       => "Привет! Это приватный бот. Если ты хочешь подключить его себе, пиши Диме в WhatsApp (https://wa.me/79994845317)",
                "parse_mode" => "html"
            ]);

            $url = $website . "/sendMessage?" . $query;

            Http::get($url);
        }
    }
}
