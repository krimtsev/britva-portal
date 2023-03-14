<?php

namespace App\Http\Controllers\Digest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Digest;
use App\Config\Constants;

class UpdateController extends Controller
{

    public function __invoke(Digest $digest, Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'is_published' => 'required|integer',
        ]);

        $digest->update($data);

        return redirect()->route('d.digest.index', $digest->id);
    }

}
