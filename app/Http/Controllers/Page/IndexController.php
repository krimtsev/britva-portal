<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;

class IndexController extends Controller
{

    public function __invoke()
    {
        $pages = Page::orderBy('id', 'DESC')->paginate(30);

        return view('dashboard.page.index', compact('pages'));
    }

}
