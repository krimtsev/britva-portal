<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class EditController extends Controller
{

    public function __invoke(User $user)
    {
        return view('dashboard.user.edit', compact('user'));
    }

}
