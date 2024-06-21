<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\User;

class EditController extends Controller
{

    public function __invoke(User $user)
    {
        $partners = Partner::select("id", "name", "contract_number", "organization")
            ->orderBy("name", "ASC")
            ->get();

        return view('dashboard.user.edit', compact('user', 'partners'));
    }

}
