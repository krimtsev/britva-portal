<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class ShowController extends Controller
{

    public function __invoke(User $user)
    {
        return view('dashboard.user.show', compact('user'));
    }

}
