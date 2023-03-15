<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;

class UpdateController extends Controller
{

    public function __invoke(User $user, Request $request)
    {
        $password = $request->input('password');
        $password_confirmation = $request->input('password_confirmation');
        $role_id = $request->input('role_id');
        $is_disabled = !!$request->input('is_disabled');

        $data = [];

        if(!is_null($password) || !is_null($password_confirmation)) {
            $validator = Validator::make(
                ['password' => $password, 'password_confirmation' => $password_confirmation],
                ['password' => ['required', 'confirmed', Rules\Password::defaults()]]
            );

            if ($validator->fails()) {
                return redirect()->route('d.user.edit', $user->id)
                    ->withErrors($validator)
                    ->withInput();
            }

            $data['password'] = Hash::make($password);
        }

        if(!is_null($role_id) && $user->role_id != $role_id) {

            $validator = Validator::make(
                ['role_id' => $role_id],
                ['role_id' => ['integer']]
            );

            if ($validator->fails()) {
                return redirect()->route('d.user.edit', $user->id)
                            ->withErrors($validator)
                            ->withInput();
            }

            $data['role_id'] = $role_id;
        }

        if(isset($is_disabled) && $user->is_disabled != $is_disabled) {
            $validator = Validator::make(
                ['is_disabled' => $is_disabled],
                ['is_disabled' => ['boolean']]
            );

            if ($validator->fails()) {
                return redirect()->route('d.user.edit', $user->id)
                            ->withErrors($validator)
                            ->withInput();
            }

            $data['is_disabled'] = $is_disabled;
        }

        if(!empty($data)) {
            $user->update($data);
        }

        return redirect()->route('d.user.index', $user->id);

    }

}
