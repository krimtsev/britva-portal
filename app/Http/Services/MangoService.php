<?php

namespace App\Http\Services;

use App\Utils\Utils;
use Illuminate\Support\Facades\Http;
use Throwable;

class MangoService
{
    const URL = "https://app.mango-office.ru";

    /** API KEY */
    private $vpbx_api_key;

    /** SALT */
    private $salt;

    /** Дата начала выборки */
    private $start_date;

    /** Дата окончания выборки */
    private $end_date;

    /** Кол-во записей */
    private $limit = 100;

    /**
     * Статус звонков
     * 1 – успешный, 0 – неуспешный.
     */
    private $context_status;

    public function __construct($params = [])
    {
        $this->vpbx_api_key = env("MANGO_VPBX_API_KEY", "");
        $this->salt = env("MANGO_SALT", "");

        if (array_key_exists("start_date", $params) &&
            array_key_exists("end_date", $params)) {
            $this->start_date = $params["start_date"];
            $this->end_date = $params["end_date"];
        }

        if (array_key_exists("limit", $params)) {
            $this->limit = $params["limit"];
        }

        if (array_key_exists("context_status", $params)) {
            $this->context_status = $params["context_status"];
        }
    }

    private function httpWithHeaders() {
        return Http::withOptions([
            "verify" => false,
        ])->asForm();
    }

    private function getHash($json) {
        return hash("sha256", $this->vpbx_api_key . $json . $this->salt);
    }

    public function get() {
        try {
            /** ПОЛУЧЕНИЕ КЛЮЧА */

            $url = Utils::joinPath(self::URL, "vpbx/stats/calls/request");

            $params = [
                "start_date"     => $this->start_date,
                "end_date"       => $this->end_date,
                "limit"          => $this->limit,
                "offset"         => "0",
                "context_type"   => 1,
            ];

            if (!is_null($this->context_status)) {
                $params["context_status"] = $this->context_status;
            }

            $json = json_encode($params);

            $sign = $this->getHash($json);

            $response = self::httpWithHeaders()->post($url, [
                "sign" => $sign,
                "vpbx_api_key" => $this->vpbx_api_key,
                "json" => $json
            ]);


            $response = $response->json();

            if (!$response["key"]) return [
                "error" => "Ошибка получения ключа"
            ];

            /** ПОЛУЧЕНИЕ ДАННЫХ ПО ЗВОНКАМ */
            sleep(30);

            $url = Utils::joinPath(self::URL, "vpbx/stats/calls/result");

            $json = json_encode([
                "key" => $response["key"],
            ]);

            $sign = $this->getHash($json);

            $response = self::httpWithHeaders()->post($url, [
                "sign" => $sign,
                "vpbx_api_key" => $this->vpbx_api_key,
                "json" => $json
            ]);

            $response = $response->json();

            /**
             * TODO: Проверить возвращение данных $response. Может быть null в $response["data"] ?
             */
            $result = array_key_exists("data", $response)
                ? $response["data"]
                : [
                    "error" => "Ошибка получения данных из Mango",
                    "response" => $response,
                  ];

            return $result;

        } catch (Throwable $e) {
            ReportService::error("[MangoService] get", $e->getMessage());
            report($e->getMessage());

            return [
                "error" => "runtime error MangoService"
            ];
        }
    }

    public function blacklist () {
        $url = Utils::joinPath(self::URL, "vpbx/bwlists/numbers");

        $sign = $this->getHash("[]");

        $response = self::httpWithHeaders()->post($url, [
            "sign" => $sign,
            "vpbx_api_key" => $this->vpbx_api_key,
            "json" => "[]"
        ]);

        $response = $response->json();

        return array_key_exists("data", $response)
            ? $response["data"]
            : [
                "error" => "Ошибка получения черного списка",
                "response" => $response,
            ];
    }
}
