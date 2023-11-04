<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;

class UpdateController extends Controller
{

    public function __invoke(User $user, Request $request)
    {
        $name = $request->input('name');
        $role_id = $request->input('role_id');
        $is_disabled = !!$request->input('is_disabled');
        $yclients_id = $request->input('yclients_id');

        $data = [];

        if(!is_null($name)) {

            $validator = Validator::make(
                ['name' => $name],
                ['name' => ['string']]
            );

            if ($validator->fails()) {
                return redirect()->route('d.user.edit', $user->id)
                            ->withErrors($validator)
                            ->withInput();
            }

            $data['name'] = $name;
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

        if(isset($yclients_id) && $user->yclients_id != $yclients_id) {
            $validator = Validator::make(
                ['yclients_id' => $yclients_id],
                ['yclients_id' => ['string']]
            );

            if ($validator->fails()) {
                return redirect()->route('d.user.edit', $user->id)
                            ->withErrors($validator)
                            ->withInput();
            }

            $data['yclients_id'] = $yclients_id;
        }

        if(!empty($data)) {
            $user->update($data);
        }

        return redirect()->route('d.user.index', $user->id);
    }

}
