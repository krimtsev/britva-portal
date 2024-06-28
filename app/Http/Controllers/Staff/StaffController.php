<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffController extends Controller
{
    const URL = "https://api.telegram.org";

    private $chatId = "";

    private function website() {
        $token = env('TELEGRAM_BOT_TOKEN', '');
        return sprintf("%s/%s", self::URL, $token);
    }

    function sendMessage($text) {
        $query = http_build_query([
            "chat_id"    => $this->chatId,
            "text"       => $text,
            "parse_mode" => "html"
        ]);

        $url =  sprintf("%s/sendMessage?%s", $this->website(), $query);

        Http::get($url);
    }

    function index(Request $request) {
        $message = $request->input("message");

        if(!$message) {
            exit;
        }

        $this->chatId = $message["chat"]["id"];

        $text = $message["text"];

        switch ($text) {
            case "/start":
                $this->actionStart();
            case "/start2":
                // function start2
            default:
                $this->sendMessage($text);
        }
    }

    function actionStart() {
        $this->sendMessage("");
    }
}
