<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use Google\Client;
use Google\Service\Sheets;

class GoogleSheetService
{

    public $client;
    public $service;

    public function __construct()
    {
        $this->client = $this->getClient();
        $this->service = new Sheets($this->client);
    }

    public function getClient()
    {

        $credentials = storage_path('google/credentials.json');

        $client = new Client();
        $client->setScopes(Sheets::SPREADSHEETS);
        $client->setAuthConfig($credentials);
        $client->setAccessType('offline');

        return $client;
    }

    public function readSheet($sheet)
    {
        $range = sprintf("%s!%s", $sheet->spreadsheet_name, $sheet->spreadsheet_range);

        $result = $this->service->spreadsheets_values->get($sheet->spreadsheet_id, $range );

        return $result->getValues();
    }
}
