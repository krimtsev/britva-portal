<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;

class ShowController extends Controller
{

    public function __invoke(Page $page)
    {
        return view('dashboard.page.show', compact('page'));
    }
}
