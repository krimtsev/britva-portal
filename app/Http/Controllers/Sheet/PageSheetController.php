<?php

namespace App\Http\Controllers\Sheet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sheet;
use App\Http\Services\GoogleSheetService;

class PageSheetController extends Controller
{
    public function __invoke($slug)
    {
        $sheet = Sheet::where('slug', '=', $slug)
            ->where('is_published', '=', 1)
            ->firstOrFail();

        $table = (new GoogleSheetService)->readSheet($sheet);

        foreach ($table as $key => $value) {
            if(count($table[0]) != count($value))
                unset($table[$key]);
        }

        $sheet->table = $table;

        if ($sheet->table_header)
            $sheet->table_header = explode(',', $sheet->table_header);

        return view('sheet.index', compact('sheet'));
    }
}


