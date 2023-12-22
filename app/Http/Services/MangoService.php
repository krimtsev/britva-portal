<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class MangoService
{
    /** API KEY */
    private $vpbx_api_key;

    /** SALT */
    private $salt;

    /** Дата начала выборки */
    private $start_date;

    /** Дата окончания выборки */
    private $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->vpbx_api_key = env('MANGO_VPBX_API_KEY', '');
        $this->salt = env('MANGO_SALT', '');
        $this->start_date = $start_date;
        $this->end_date = $end_date;
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

            $url = "https://app.mango-office.ru/vpbx/stats/calls/request";

            $json = json_encode([
                "start_date" => $this->start_date,
                "end_date" => $this->end_date,
                "limit" => "100",
                "offset" => "0",
                "context_type" => 1,
                "context_status" => 0
            ]);

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
            sleep(5);

            $url = "https://app.mango-office.ru/vpbx/stats/calls/result";

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

            $result = array_key_exists("data", $response)
                ? $response["data"]
                : [];

            dd($result);

            return $result;

        } catch (Throwable $e) {
            report($e);
            return [
                "error" => $e
            ];
        }
    }
}
