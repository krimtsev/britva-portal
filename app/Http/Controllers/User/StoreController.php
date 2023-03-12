<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;

class StoreController extends Controller
{

    public function __invoke(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string', 'min:3', 'max:50', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'login' => $request->login,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->route('d.user.index');
    }

}

