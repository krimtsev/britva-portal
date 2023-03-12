<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Page;

class UpdateController extends Controller
{

    public function __invoke(Page $page, Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'is_published' => 'required|integer',
            'slug' => 'required|string|max:255|unique:pages,slug,'.$page->id,
        ]);

        $page->update($data);

        return redirect()->route('d.page.index', $page->id);
    }
}
