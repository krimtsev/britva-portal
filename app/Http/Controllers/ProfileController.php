<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return view('profile.user.index');
    }

    public function update(Request $request)
    {
        $password = $request->input('password');
        $password_confirmation = $request->input('password_confirmation');

        $user = Auth::user();

        $data = [];

        if(!is_null($password) || !is_null($password_confirmation)) {
            $validator = Validator::make(
                ['password' => $password, 'password_confirmation' => $password_confirmation],
                ['password' => ['required', 'confirmed', Rules\Password::defaults()]]
            );

            if ($validator->fails()) {
                return redirect()->route('d.user.password.index', $user->id)
                    ->withErrors($validator)
                    ->withInput();
            }

            $data['password'] = Hash::make($password);
        }

        if(!empty($data)) {
            $user->update($data);
        }

        return redirect()->route('p.user.index');
    }
}
