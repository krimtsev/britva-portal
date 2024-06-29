<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use App\Models\Partner;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffController extends Controller
{
    const URL = "https://api.telegram.org";

    private $chatId = "";
    private $text = "";

    public $messages = [
        "start" => [
            "Добро пожаловать в BRITVA STATS BOT!",
            "Здесь можно узнать информацию по своей карточке сотрудника и сравнить её с коллегами в своем филиале.",
            "Для регистрации введите ID филиала."
        ],
        "yclients_success" => [
            "Выбран филиал: " => "yclients_name",
            "Введите ID сотрудника."
        ],
        "yclients_error" => [
            "Указан не правильный ID филиала."
        ],
    ];

    public $actions = [
        "yclients_id" => "yclients_id",
        "staff_id"    => "staff_id",
    ];

    public function getMessages($key, $values = []) {
        $message= "";

        if (!array_key_exists($key, $this->messages)) {
            return $message;
        }

        foreach ($this->messages[$key] as $str => $val) {
            if (array_key_exists($val, $values)) {
                $message .= $str . $values[$val] . "\n";
            } else {
                $message .= $str . "\n";
            }
        }

        return $message;
    }

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

        // Http::get($url);
        file_get_contents($url);
    }

    function index(Request $request) {
        $message = $request->input("message");

        if(!$message) {
            exit;
        }

        $this->chatId = $message["chat"]["id"];

        $this->text = $message["text"];

        $isCommand = str_starts_with($this->text, '/');

        if ($isCommand) {
            switch ($this->text) {
                case "/start":
                    $this->actionStart();
                    break;
                default:
                    $this->sendMessage("DEFAULT");
            }
        } else {
            $action = Staff::select("action")
                ->where('tg_chat_id', $this->chatId)
                ->first();

            if (!$action) return response("1", 200);

            switch ($action) {
                case $this->actions["yclients_id"]:
                    $this->actionYclientsId();
                    break;
                case $this->actions["staff_id"]:
                    $this->actionStaffId();
                    break;
            }

        }

        return response("1", 200);
    }

    function actionStart() {
        $this->sendMessage($this->getMessages("start"));

        Staff::addStaff([
            "action"     => $this->actions["yclients_id"],
            "tg_chat_id" => $this->chatId
        ]);
    }

    function actionYclientsId() {
        $partner = Partner::select("name")
            ->where('yclients_id', $this->text)
            ->first();

        if ($partner) {
            $this->sendMessage($this->getMessages("yclients_success", [
                "yclients_name" => $partner->name
            ]));

            Staff::where("tg_chat_id", $this->chatId)->update([
                "action" => $this->actions["staff_id"],
            ]);
        } else {
            $this->sendMessage($this->getMessages("yclients_error"));
        }
    }

    function actionStaffId() {
/*        $partner = Partner::select("name")
            ->where('yclients_id', $this->text)
            ->first();

        if ($partner) {
            $this->sendMessage($this->getMessages("yclients_success", [
                "yclients_name" => $partner->name
            ]));

            Staff::where("tg_chat_id", $this->chatId)->update([
                "action" => $this->actions["staff_id"],
            ]);
        } else {
            $this->sendMessage($this->getMessages("yclients_error"));
        }*/
    }
}
