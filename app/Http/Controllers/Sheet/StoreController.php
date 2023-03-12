<?php

namespace App\Http\Controllers\Sheet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sheet;

class StoreController extends Controller
{

    public function __invoke(Request $request)
    {

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'spreadsheet_id' => 'required|string|max:255',
            'spreadsheet_name' => 'required|string|max:255',
            'spreadsheet_range' => 'required|string|max:255',
            'slug' => 'required|string|unique:sheets|max:255',
            'table_header' => 'string|max:255',
        ]);

        $data['user_id'] = $request->user()->id;

        Sheet::create($data);

        return redirect()->route('d.sheet.index');
    }
}
