<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class ReportService
{
    const URL = "https://britva.tech/report.php";

    static function send($id, $msg)
    {
        try {
            $partnerName = env('PARTNER_NAME', '');

            $str = explode($msg, "\r\n", 1);
            $message = sprintf("[ERROR] [%s] %s %s", $partnerName, $id, $str[0]);
            //$message = substr("[ERROR] [{$partnerName}] {$id} {$msg}", 0, 400);

            // Native report
            report($message);

            $query = http_build_query([
                "msg" => $message,
            ]);

            $url = sprintf("%s?%s", self::URL, $query);

            Http::get($url);
        } catch (Throwable $e) {
            report($e->getMessage());
        }
    }
}
