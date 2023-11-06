<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class IndexController extends Controller
{

    public function __invoke()
    {
        $users = User::orderBy('id', 'DESC')->paginate(250);

        return view('dashboard.user.index', compact('users'));
    }

}
