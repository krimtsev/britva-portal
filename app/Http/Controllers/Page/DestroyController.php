<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;

class DestroyController extends Controller
{

    public function __invoke(Page $page)
    {
        $page->delete();

        return redirect()->route('d.page.index');
    }

}
