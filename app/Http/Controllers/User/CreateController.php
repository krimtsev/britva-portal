<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Partner;

class CreateController extends Controller
{

    public function __invoke()
    {
        $partners = Partner::available();

        return view('dashboard.user.create', compact('partners'));
    }

}
