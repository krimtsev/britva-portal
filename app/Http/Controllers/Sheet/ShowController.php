<?php

namespace App\Http\Controllers\Sheet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sheet;
use App\Http\Services\GoogleSheetService;

class ShowController extends Controller
{

    public function __invoke(Sheet $sheet)
    {
        $table = (new GoogleSheetService)->readSheet($sheet);

        foreach ($table as $key => $value) {
            if(count($table[0]) != count($value))
                unset($table[$key]);
        }

        $sheet->table = $table;

        if ($sheet->table_header)
            $sheet->table_header = explode(',', $sheet->table_header);

        return view('dashboard.sheet.show', compact('sheet'));
    }
}
