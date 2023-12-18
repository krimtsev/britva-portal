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

    public function __construct()
    {
        $this->vpbx_api_key = env('MANGO_VPBX_API_KEY', '');
        $this->salt = env('MANGO_SALT', '');
    }

    private function httpWithHeaders() {
        return Http::withOptions([
            "verify" => false,
        ])->asForm();
    }

    public function get() {
        try {
            /** ПОЛУЧЕНИЕ КЛЮЧА */

            $url = "https://app.mango-office.ru/vpbx/stats/calls/request";

            $json = json_encode([
                "start_date" => "17.12.2023 00:00:00",
                "end_date" => "17.12.2023 23:59:59",
                "limit" => "5000",
                "offset" => "0",
                "context_type" => 1,
                "context_status" => 0
            ]);

            $sign = hash("sha256", $this->vpbx_api_key . $json . $this->salt);

            $response = self::httpWithHeaders()->post($url, [
                "sign" => $sign,
                "vpbx_api_key" => $this->vpbx_api_key,
                "json" => $json
            ]);


            $response = $response->json();

            if (!$response["key"]) return false;

            /** ПОЛУЧЕНИЕ ДАННЫХ ПО ЗВОНКАМ */

            $url = "https://app.mango-office.ru/vpbx/stats/calls/result";

            $json = json_encode([
                "key" => $response["key"],
            ]);

            $sign = hash("sha256", $this->vpbx_api_key . $json . $this->salt);

            $response = self::httpWithHeaders()->post($url, [
                "sign" => $sign,
                "vpbx_api_key" => $this->vpbx_api_key,
                "json" => $json
            ]);

            $response = $response->json();

            return $response["data"][0];

        } catch (Throwable $e) {
            report($e);
            return $e;
        }
    }
}
