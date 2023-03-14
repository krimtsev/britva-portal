<?php

namespace App\Http\Controllers\Digest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Digest;

class EditController extends Controller
{

    public function __invoke(Digest $digest)
    {
        return view('dashboard.digest.edit', compact('digest'));
    }

}
