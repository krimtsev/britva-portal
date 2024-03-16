<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;

class StoreController extends Controller
{

    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'min:3', 'max:50', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'partner_id'  => 'nullable|string',
        ]);

        // Задаем partner_id как 0 если отвязываем его от пользователя
        $partner_id = !is_null($request->partner_id) ? $request->partner_id : 0;

        $user = User::create([
            'login'      => $request->login,
            'password'   => Hash::make($request->password),
            "partner_id" => $partner_id
        ]);

        event(new Registered($user));

        return redirect()->route('d.user.index');
    }

}

