<?php

namespace App\Http\Controllers\Digest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Digest;

class DigestController extends Controller
{

    public function __invoke()
    {
        $digest = Digest::where('is_published', '=', 1)->orderBy('id', 'DESC')->take(5)->get();

        return view('digest.index', compact('digest'));
    }

}
