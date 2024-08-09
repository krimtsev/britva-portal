<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Analytics\BranchReport;
use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use App\Http\Services\YclientsService;
use App\Models\Partner;
use App\Models\Staff;
use Carbon\Carbon;
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
            "Выбран сотрудник: " => "staff_name",
            "Выберите дальнейшие действия"
        ],
        "staff_error" => [
            "Указан не правильный ID сотрудника.",
            "Введите корректный ID сотрудника. Его можно узнать у Администратора в разделе Настройки > Сотрудники > выбрать сотрудника > посмотреть ID в адресной строке"
        ],
    ];

    public $customCommands = ["Личная статистика"];

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

    private function getInlineKeyBoard($data) {
        return json_encode([
            "inline_keyboard" => $data,
        ]);
    }

    private function getKeyBoard($data) {
        return json_encode([
            "keyboard" => $data,
            "one_time_keyboard" => false,
            "resize_keyboard" => true
        ]);
    }

    function sendMessage($text, $args = []) {
        $params = [
            "chat_id"    => $this->chatId,
            "text"       => $text,
            "parse_mode" => "html",
        ];

        if (array_key_exists("inline_keyboard", $args)) {
            $params["reply_markup"] = $this->getInlineKeyBoard($args["inline_keyboard"]);
        }

        if (array_key_exists("keyboard", $args)) {
            $params["reply_markup"] = $this->getKeyBoard($args["keyboard"]);
        }

        $query = http_build_query($params);

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
            //$callback_query = $request->input("callback_query");

            if(!$message) {
                return $this->response();
            }

            $this->chatId = $message["chat"]["id"];

            $this->text = $message["text"];

            $isCommand = str_starts_with($this->text, '/');

            ReportService::send("message", json_encode($message));
            //ReportService::send("callback_query", json_encode($content));

            if ($isCommand) {
                switch ($this->text) {
                    case "/start":
                        $this->actionStart();
                        break;
                    case "/buttons":
                        $this->showButtons();
                        break;
                    default:
                        $this->sendMessage("DEFAULT");
                }
            } else {
                if (in_array($this->text, $this->customCommands)) {
                    switch ($this->text) {
                        case "Личная статистика":
                            $this->showStatistics();
                            break;
                    }

                } else {
                    $data = Staff::select("action")
                        ->where('tg_chat_id', $this->chatId)
                        ->first();

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
            }
        } catch (Throwable $e) {
            ReportService::send("api", $e->getMessage());
        }

        return $this->response();
    }

    function actionStart() {
        $this->sendMessage($this->getMessages("start"));

        Staff::add([
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
            Staff::where("tg_chat_id", $this->chatId)->update([
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
        $staff = Staff::select("yclients_id")
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

            Staff::where("tg_chat_id", $this->chatId)->update([
                "action"   => "",
                "staff_id" => $staffData["id"],
                "name"     => $staffData["name"],
            ]);
        } else {
            $this->sendMessage($this->getMessages("staff_error"));
        }
    }

    private function showButtons() {
        $this->sendMessage("text", [
            "keyboard" => [
                [
                    ["text" => "Личная статистика"],
                ],
                [
                    ["text" => "Button 6"],
                ]
            ]
        ]);
    }

    private function showStatistics() {
/*        $staff = Staff::select("yclients_id")
            ->where('tg_chat_id', $this->chatId)
            ->first();

        $start_date = Carbon::now()->startOfDay()->format('Y-m-d');
        $end_date = Carbon::now()->format('Y-m-d');
        $company_id = $staff->yclients_id;

        $branchReport = new BranchReport($start_date, $end_date, $company_id);

        $this->sendMessage("showStatistics");*/
    }
}
