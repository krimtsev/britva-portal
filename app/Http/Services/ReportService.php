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

            $message = "[ERROR] [{$partnerName}] {$id} {$msg}";

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
