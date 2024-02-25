<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $login = $user->login;

        $partner = false;
        if ($user->partner_id) {
            $partner = Partner::where('id', '=', $user->partner_id)->firstOr(function () {
                return false;
            });
        }

        return view('profile.home.index', compact('login', 'partner'));
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
