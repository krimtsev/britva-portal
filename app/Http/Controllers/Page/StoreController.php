<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;

class StoreController extends Controller
{

    public function __invoke(Request $request)
    {

        $data = request()->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'slug' => 'required|string|unique:pages|max:255',
        ]);

        $data['user_id'] = $request->user()->id;

        Page::create($data);

        return redirect()->route('d.page.index');
    }

}
