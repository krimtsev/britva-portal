<?php

namespace App\Http\Controllers\Mango;

use App\Http\Controllers\Controller;
use App\Models\MangoBlacklist;
use Illuminate\Http\Request;

class BlackListController extends Controller
{
    public function index() {
        $blacklist = MangoBlacklist::orderBy('number', 'asc')
            ->paginate(50);

        return view('dashboard.blacklist.index', compact("blacklist"));
    }

    public function edit(MangoBlacklist $blacklist) {
        return view('dashboard.blacklist.edit', compact("blacklist"));
    }

    public function update(MangoBlacklist $blacklist, Request $request) {
        $data = $request->validate([
            "is_disabled" => "required",
        ]);

        $blacklist->update([
            "is_disabled" => filter_var($data["is_disabled"], FILTER_VALIDATE_BOOLEAN)
        ]);

        return redirect()->route('d.blacklist.index');
    }
}
