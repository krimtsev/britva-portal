<?php

namespace App\Http\Controllers\Sheet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sheet;

class EditController extends Controller
{

    public function __invoke(Sheet $sheet)
    {
        return view('dashboard.sheet.edit', compact('sheet'));
    }
}
