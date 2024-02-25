<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnersController extends Controller
{

    public function index()
    {
        $partners = Partner::orderBy('id', 'DESC')->paginate(200);

        return view('dashboard.partner.index', compact('partners'));
    }

    public function create()
    {
        return view('dashboard.partner.create');
    }

    public function store(Request $request)
    {
        $data = request()->validate([
            'organization'    => 'nullable|string|max:255',
            'name'            => 'required|string|max:255|unique:partners',
            'contract_number' => 'required|string|max:50|unique:partners',
            'email'           => 'email|nullable',
            'telnum_1'        => 'nullable|string|min:12|max:12',
            'telnum_2'        => 'nullable|string|min:12|max:12',
            'telnum_3'        => 'nullable|string|min:12|max:12',
            'yclients_id'     => 'nullable|string|max:255',
            'address'         => 'nullable|string|max:255',
            'start_at'        => 'nullable|date_format:Y-m-d',
        ]);

        Partner::create($data);

        return redirect()->route('d.partner.index');
    }

    public function edit(Partner $partner)
    {
        return view('dashboard.partner.edit', compact('partner'));
    }

    public function update(Partner $partner, Request $request)
    {
        $data = $request->validate([
            'organization'    => 'nullable|string|max:255',
            'name'            => 'required|string|max:255|unique:partners,name,'.$partner->id,
            'contract_number' => 'required|string|max:50|unique:partners,contract_number,'.$partner->id,
            'email'           => 'email|nullable',
            'telnum_1'        => 'nullable|string|min:12|max:12',
            'telnum_2'        => 'nullable|string|min:12|max:12',
            'telnum_3'        => 'nullable|string|min:12|max:12',
            'yclients_id'     => 'nullable|string|max:255',
            'address'         => 'nullable|string|max:255',
            'start_at'        => 'nullable|date_format:Y-m-d',
        ]);

        $partner->update($data);

        return redirect()->route('d.partner.index');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('d.partner.index');
    }


    public function partnerIndex() {
        $partners = Partner::orderBy('id', 'DESC')->paginate(200);

        return view('profile.partners.index', compact('partners'));
    }
}
