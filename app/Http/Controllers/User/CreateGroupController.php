<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateGroupController extends Controller
{

    public function index()
    {
        return view('dashboard.user.create-group');
    }

    public function store(Request $request)
    {
        $userErrors = [];

        $list = explode("\n", str_replace(array("\r", " "), "", $request->list));

        foreach($list as $item){
            [$login, $password] = explode(':', $item);

            $data['login'] = $login;
            $data['password'] = $password;

            $validator = Validator::make(
                $data,
                [
                    'login' => ['required', 'string', 'min:3', 'max:50', 'unique:users'],
                    'password' => ['required', 'min:8']
                ]
            );

            if($validator->fails())
            {
                $userErrors[] = array('user' => $item, 'errors' => $validator->errors()->first());
                continue;
            }

            $user = User::create([
                'login' => $data['login'],
                'password' => Hash::make($data['password']),
            ]);

            event(new Registered($user));
        }

        $userErrors = json_encode($userErrors);

        return view('dashboard.user.create-group', compact('userErrors'));
    }

}
