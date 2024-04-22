<?php

namespace App\Http\Controllers\Partners;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnersController extends Controller
{

    public function index(Request $request)
    {
        $is_disabled = (bool) $request->query("disabled");

        $partners = Partner::orderBy('name', 'ASC')
            ->where('disabled', '=', $is_disabled)
            ->paginate(200);

        return view('dashboard.partner.index', compact('partners', 'is_disabled'));
    }

    public function create()
    {
        return view('dashboard.partner.create');
    }

    public function store(Request $request)
    {
        $data = request()->validate([
            'inn'               => 'nullable|string|max:12',
            'organization'      => 'nullable|string|max:255',
            'name'              => 'required|string|max:255|unique:partners',
            'contract_number'   => 'required|string|max:50|unique:partners',
            'email'             => 'email|nullable',
            'telnums.*.number'  => 'nullable|string|min:11|max:12',
            'telnums.*.name'    => 'nullable|string|max:50',
            'yclients_id'       => 'nullable|string|max:255',
            'mango_telnum'      => 'nullable|string|min:11|max:12',
            'address'           => 'nullable|string|max:255',
            'start_at'          => 'nullable|date_format:Y-m-d',
        ]);

        $data["telnums"] = json_encode($data["telnums"]);

        $data['disabled'] = !is_null($request->disabled) ? true : false;

        Partner::create($data);

        return redirect()->route('d.partner.index');
    }

    public function edit(Partner $partner)
    {
        if (!$partner->telnums) {
            $partner->telnums = array_fill(0, 3, [ "number" => "", "name" => ""]);
        } else {
            $partner->telnums = json_decode($partner->telnums, true);
        }

        return view('dashboard.partner.edit', compact('partner'));
    }

    public function update(Partner $partner, Request $request)
    {
        $data = $request->validate([
            'inn'               => 'nullable|string|max:12',
            'organization'      => 'nullable|string|max:255',
            'name'              => 'required|string|max:255|unique:partners,name,'.$partner->id,
            'contract_number'   => 'required|string|max:50|unique:partners,contract_number,'.$partner->id,
            'email'             => 'email|nullable',
            'telnums.*.number'  => 'nullable|string|min:11|max:12',
            'telnums.*.name'    => 'nullable|string|max:50',
            'yclients_id'       => 'nullable|string|max:255',
            'mango_telnum'      => 'nullable|string|min:11|max:12',
            'address'           => 'nullable|string|max:255',
            'start_at'          => 'nullable|date_format:Y-m-d',
        ]);

        if (array_filter($data["telnums"], function($k) { return !is_null($k["number"]); })) {
            $data["telnums"] = json_encode($data["telnums"]);
        } else {
            $data["telnums"] = null;
        }

        $data['disabled'] = !is_null($request->disabled) ? true : false;

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

    public function contacts() {
        $result = Partner::select('id', 'name', 'telnums')
            ->where('disabled', '=', false)
            ->get();

        $partners = [];

        foreach ($result as $one) {
            $id = $one->id;
            $partners[$id] = [
                "name" => $one->name,
                "telnums" => []
            ];

            if (!is_null($one->telnums)) {
                $telnums = json_decode($one->telnums, true);

                foreach ($telnums as $telnum) {


                    if (!is_null($telnum["number"])) {
                        $partners[$id]["telnums"][] = [
                            "number" => $telnum["number"],
                            "name" => $telnum["name"]
                        ];
                    }
                }
            }

        }

        return view('static.contact-franchise', compact('partners'));
    }


    /*
    // Выполнялось для заполнения телефонов по списку
    public function updateTelnumsByJSONList() {

        $list = [
            ["74994606679", "496409"],
        ];

        foreach ($list as [$telnum, $yclients_id]) {
            Partner::where("yclients_id", $yclients_id)->update(['mango_telnum' => $telnum]);
        }
    }
    */
}
