<?php

namespace App\Http\Controllers\Digest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Digest;

class DestroyController extends Controller
{

    public function __invoke(Digest $digest)
    {
        $digest->delete();

        return redirect()->route('d.digest.index');
    }

}
