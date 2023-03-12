<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{

    public function __invoke($slug)
    {
        $page = Page::where('slug', '=', $slug)
            ->where('is_published', '=', 1)
            ->firstOrFail();

        return view('page.index', compact('page'));
    }

}
