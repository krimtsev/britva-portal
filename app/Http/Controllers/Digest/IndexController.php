<?php

namespace App\Http\Controllers\Digest;

use App\Http\Controllers\Controller;
use App\Models\Digest;

class IndexController extends Controller
{

    public function __invoke()
    {
        $digests = Digest::orderBy('id', 'DESC')->paginate(30);

        return view('dashboard.digest.index', compact('digests'));
    }
}
