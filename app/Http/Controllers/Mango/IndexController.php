<?php

namespace App\Http\Controllers\Mango;

use App\Http\Services\MangoService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input("date");

        $service = new MangoService($date);

        $data = $service->get();

        $table = [];

        foreach ($data["list"] as $one) {
            $table[] = [
                "name" => !empty($one["context_calls"]) ? $one["context_calls"][0]["call_abonent_info"] : "",
                "caller_number" => $one["caller_number"],
                "called_number" => $one["called_number"],
                "context_start_time" => date('m/d/Y h:i:s', $one["context_start_time"]),
                "call_duration" => !empty($one["context_calls"]) ? $one["context_calls"][0]["call_duration"] : ""
            ];
        }

        dd($table);
    }

}
