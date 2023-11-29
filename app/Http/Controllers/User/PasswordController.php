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
        $request->validate([
            'password' => 'required|same:password_confirmation|min:6',
            'password_confirmation' => 'required',
        ]);

        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->route('d.user.edit', $user->id);
    }
}
