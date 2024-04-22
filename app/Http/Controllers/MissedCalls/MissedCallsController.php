<?php

namespace App\Http\Controllers\MissedCalls;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class MissedCallsController extends Controller
{

    public function index()
    {
        $partners = Partner::select("id", "name", "yclients_id", "tg_active", "tg_chat_id", "tg_pay_end")
            ->orderBy('name', 'asc')
            ->paginate(200);

        return view('dashboard.missed-calls.index', compact('partners'));
    }

    public function edit(Partner $partner)
    {
        return view('dashboard.missed-calls.edit', compact('partner'));
    }

    public function update(Partner $partner, Request $request)
    {
        $data = $request->validate([
            'tg_active'       => 'boolean',
            'tg_chat_id'      => 'nullable|string|max:255',
            'tg_pay_end'      => 'nullable|date_format:Y-m-d',
        ]);

        $partner->update($data);

        return redirect()->route('d.missed-calls.index');
    }


/*    public function partnerIndex() {
        $partners = Partner::orderBy('id', 'DESC')->paginate(200);

        return view('profile.partners.index', compact('partners'));
    }*/

}
