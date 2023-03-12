<?php

namespace App\Http\Controllers\Sheet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sheet;

class DestroyController extends Controller
{

    public function __invoke(Sheet $sheet)
    {
        $sheet->delete();

        return redirect()->route('d.sheet.index');
    }
}
