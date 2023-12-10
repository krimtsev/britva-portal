<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = [
            "login" => $user->login,
            "name" => $user->name,
        ];

        return view('profile.home.index', compact('data'));
    }

    public function changePasswordIndex()
    {
        return view('profile.user.index');
    }

    public function changePasswordUpdate(Request $request)
    {
        $user = Auth::user();

        $userPassword = $user->password;

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|same:password_confirmation|min:6',
            'password_confirmation' => 'required',
        ]);

        if (!Hash::check($request->current_password, $userPassword)) {
            return back()->withErrors(['current_password'=>'current password not match']);
        }

        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->route('p.user.index');
    }
}
