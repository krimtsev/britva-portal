<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function index(User $user)
    {
        return view('dashboard.user.password', compact('user'));
    }

    public function update(User $user, Request $request)
    {
        $password = $request->input('password');
        $password_confirmation = $request->input('password_confirmation');

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

        return redirect()->route('d.user.edit', $user->id);
    }
}
