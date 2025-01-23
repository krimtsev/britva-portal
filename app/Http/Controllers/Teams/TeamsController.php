<?php

namespace  App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamsController extends Controller
{
    const RULES_ALLOW_TYPES = 'mimes:image,jpg,jpeg,png';

    /**
     * LOCAL METHODS
     */

    function index() {
        $teams = Team::orderBy('id', 'DESC')->paginate(30);
        $rolesList = Team::$rolesList;
        $partners = Partner::getPartnersName();

        return view('dashboard.teams.index', compact(
            'teams',
            'rolesList',
            'partners'
        ));
    }

    function create() {
        $rolesList = Team::$rolesList;
        $partners = Partner::getPartnersName();

        return view('dashboard.teams.create', compact(
            'rolesList',
            'partners'
        ));

    }

    function store(Request $request) {
        $data = $request->validate([
            'photo'      => ['nullable', self::RULES_ALLOW_TYPES],
            'name'       => 'required|min:3',
            'partner_id' => 'required',
            'role_id'    => 'required',
        ]);

        if (array_key_exists("photo", $data)) {
            $file = $data["photo"];
            $path = Storage::disk("public")->put(Team::FOLDER,  $file);
            $data["photo"] = $path;
        }

        Team::create($data);

        return redirect()->route('d.teams.index');
    }

    public function edit(Team $team)
    {
        $rolesList = Team::$rolesList;
        $partners = Partner::getPartnersName();

        return view('dashboard.teams.edit', compact(
            'team',
            'rolesList',
            'partners'
        ));
    }

    function update(Team $team, Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'photo'      => ['nullable', self::RULES_ALLOW_TYPES],
            'name'       => 'required|min:3',
            'partner_id' => 'required',
            'role_id'    => 'required',
        ]);

        if (array_key_exists("photo", $data)) {
            $file = $data["photo"];
            $path = Storage::disk("public")->put(Team::FOLDER,  $file);
            $data["photo"] = $path;
        }

        $team->update($data);

        return redirect()->route('d.teams.index');
    }
}
