<?php

namespace App\Http\Controllers\Digest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Digest;

class StoreController extends Controller
{

    public function __invoke(Request $request)
    {
        $data = request()->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $data['user_id'] = $request->user()->id;

        Digest::create($data);

        return redirect()->route('d.digest.index');
    }

}
