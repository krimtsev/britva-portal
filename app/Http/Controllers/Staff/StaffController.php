<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use App\Http\Services\YclientsService;
use App\Models\Partner;
use App\Models\StaffBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class StaffController extends Controller
{
    const URL = "https://api.telegram.org";

    private $chatId = "";
    private $text = "";

    public $messages = [
        "start" => [
            "Добро пожаловать в BRITVA STATS BOT!",
            "Здесь можно узнать информацию по своей карточке сотрудника и сравнить её с коллегами в своем филиале.",
            "Для регистрации введите ID филиала:"
        ],
        "yclients_success" => [
            "Выбран филиал: " => "yclients_name",
            "Введите ID сотрудника:"
        ],
        "yclients_error" => [
            "Указан не правильный ID филиала.",
            "Введите корректный ID филиала. Его можно узнать у Администратора в разделе Обзор > Сводка"
        ],
        "staff_success" => [
            "Выбран сотрудник: " => "staff_name"
        ],
        "staff_error" => [
            "Указан не правильный ID сотрудника.",
            "Введите корректный ID сотрудника. Его можно узнать у Администратора в разделе Настройки > Сотрудники > выбрать сотрудника > посмотреть ID в адресной строке"
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
                $message .= $val . "\n";
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

        Http::get($url);
        //file_get_contents($url);
    }

    function response() {
        return response("1", 200);
    }

    function index(Request $request) {
        try {
            //$content = json_decode(file_get_contents("php://input"), true);
            $message = $request->input("message");

            if(!$message) {
                return $this->response();
            }

            $this->chatId = $message["chat"]["id"];

            $this->text = $message["text"];

            $isCommand = str_starts_with($this->text, '/');

            ReportService::send("request", json_encode($message));

            if ($isCommand) {
                switch ($this->text) {
                    case "/start":
                        $this->actionStart();
                        break;
                    default:
                        $this->sendMessage("DEFAULT");
                }
            } else {
                $data = StaffBot::select("action")
                    ->where('tg_chat_id', $this->chatId)
                    ->first();

                ReportService::send("action", json_encode($data->action));

                if (!$data) exit;

                switch ($data->action) {
                    case $this->actions["yclients_id"]:
                        $this->actionYclientsId();
                        break;
                    case $this->actions["staff_id"]:
                        $this->actionStaffId();
                        break;
                }

            }
        } catch (Throwable $e) {
            ReportService::send("api", $e->getMessage());
        }

        return $this->response();
    }

    function actionStart() {
        $this->sendMessage($this->getMessages("start"));

        StaffBot::add([
            "tg_chat_id"  => $this->chatId,
            "action"      => $this->actions["yclients_id"],
            "name"        => "",
            "yclients_id" => "",
            "staff_id"    => "",
        ]);
    }

    private function actionYclientsId() {
        $partner = Partner::select("name")
            ->where('yclients_id', $this->text)
            ->first();

        if ($partner) {
            StaffBot::where("tg_chat_id", $this->chatId)->update([
                "yclients_id" => $this->text,
                "action"      => $this->actions["staff_id"],
            ]);

            $this->sendMessage($this->getMessages("yclients_success", [
                "yclients_name" => $partner->name
            ]));
        } else {
            $this->sendMessage($this->getMessages("yclients_error"));
        }
    }

    private function actionStaffId() {
        $staff = StaffBot::select("yclients_id")
            ->where('tg_chat_id', $this->chatId)
            ->first();

        $params = [
            "company_id" => $staff->yclients_id
        ];

        $client = new YclientsService($params);
        $staffData = $client->getStaffData($this->text);

        if (is_array($staffData)) {
            $this->sendMessage($this->getMessages("staff_success", [
                "staff_name" => $staffData["name"]
            ]));

            StaffBot::where("tg_chat_id", $this->chatId)->update([
                "action"   => "",
                "staff_id" => $staffData["id"],
                "name"     => $staffData["name"],
            ]);
        } else {
            $this->sendMessage($this->getMessages("staff_error"));
        }
    }
}
