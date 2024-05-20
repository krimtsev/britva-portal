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
        $data = $request->validate([
            'login'       => 'required|string|min:3|max:50|unique:users,login,'.$user->id,
            'role_id'     => 'required|integer|regex:/[1-3]{1}/',
            'is_disabled' => 'nullable|string',
            'partner_id'  => 'nullable|string',
        ]);

        if(!is_null($request->name)) {
            $validator = Validator::make(
                ['name' => $request->name],
                ['name' => ['string', 'max:50']]
            );

            if ($validator->fails()) {
                return redirect()->route('d.user.edit', $user->id)
                    ->withErrors($validator)
                    ->withInput();
            }

            $data['name'] = $request->name;
        } else {
            $data['name'] = "";
        }

        if (!is_null($request->is_disabled)) {
            $data['is_disabled'] = 1;
        } else {
            $data['is_disabled'] = 0;
        }

        // Задаем partner_id как 0 если отвязываем его от пользователя
        if (is_null($data['partner_id'])) {
            $data['partner_id'] = 0;
        }

        $user->update($data);

        return redirect()->route('d.user.index', $user->id);
    }

}
