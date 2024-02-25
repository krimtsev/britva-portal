<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Partner;

class CreateController extends Controller
{

    public function __invoke()
    {
        $partners = Partner::select("id", "name", "contract_number", "organization")
            ->where('yclients_id', '<>', "")
            ->orderBy("name")
            ->get();

        return view('dashboard.user.create', compact('partners'));
    }

}
