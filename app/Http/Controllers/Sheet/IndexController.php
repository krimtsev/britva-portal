<?php

namespace App\Http\Controllers\Sheet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sheet;

class IndexController extends Controller
{

    public function __invoke()
    {
        $sheets = Sheet::orderBy('id', 'DESC')->paginate(30);

        return view('dashboard.sheet.index', compact('sheets'));
    }
}
