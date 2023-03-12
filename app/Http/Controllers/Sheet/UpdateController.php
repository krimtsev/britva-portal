<?php

namespace App\Http\Controllers\Sheet;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Sheet;

class UpdateController extends Controller
{

    public function __invoke(Sheet $sheet, Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'spreadsheet_id' => 'required|string|max:255',
            'spreadsheet_name' => 'required|string|max:255',
            'spreadsheet_range' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:sheets,slug,'.$sheet->id,
            'table_header' => 'nullable|string|max:255',
            'is_published' => 'required|integer',
        ]);

        $sheet->update($data);

        return redirect()->route('d.sheet.index', $sheet->id);
    }
}
