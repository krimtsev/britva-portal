<?php

namespace  App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Team;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeamsController extends Controller
{
    const RULES_ALLOW_TYPES = 'mimes:image,jpg,jpeg,png';

    /**
     * LOCAL METHODS
     */

    public function isProfile(Request $request): bool
    {
        return $request->route()->getAction("view") == "profile";
    }

    function index(Request $request) {
        $isProfile = $this->isProfile($request);
        $rolesList = Team::$rolesList;

        /**
         * Профиль пользователя
         */

        if ($isProfile) {
            $user = Auth::user();
            $partner_id = $user->partner_id;

            $isAllowedEditInProfile = Partner::sqlAvailable()
                ->where('id', $partner_id)
                ->exists();

            if (!$isAllowedEditInProfile) {
                return view('profile.teams.index', compact(
                    'isAllowedEditInProfile',
                ));
            }

            $teams = Team::orderBy('id', 'ASC')
                ->where('partner_id', $partner_id)
                ->paginate(30);

            $partners = Partner::getAllPartnersName();

            return view('profile.teams.index', compact(
                'teams',
                'rolesList',
                'partner_id',
                'partners',
                'isAllowedEditInProfile'
            ));
        }

        /**
         * Панель администратора
         */

        $filter = [];
        $teams_sql = Team::orderBy('id', 'DESC');
        $partners = Partner::getAllPartnersName();

        $filter['partner'] = $request->input("filter_partner");

        if ($filter['partner']) {
            $teams_sql->where("partner_id", $filter['partner']);
        }

        $teams = $teams_sql->paginate(30);

        return view('dashboard.teams.index', compact(
            'teams',
            'rolesList',
            'partners',
            'filter'
        ));
    }

    function create(Request $request) {
        $isProfile = $this->isProfile($request);

        $rolesList = Team::$rolesList;

        /**
         * Профиль пользователя
         */

        if ($isProfile) {
            $user = Auth::user();

            if (!$user->partner_id) {
                return redirect()->route('p.teams.index');
            }

            return view('profile.teams.create', compact(
                'rolesList',
            ));
        }

        /**
         * Панель администратора
         */

        $partners = Partner::getPartnersName();

        return view('dashboard.teams.create', compact(
            'rolesList',
            'partners'
        ));
    }

    function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $isProfile = $this->isProfile($request);
        $data = $request->all();
        $redirect_url = route('d.teams.index');

        $rules = [
            'photo'       => ['nullable', self::RULES_ALLOW_TYPES],
            'name'        => 'required',
            'description' => 'nullable',
            'partner_id'  => 'required',
            'role_id'     => 'required',
        ];

        if ($isProfile) {
            $user = Auth::user();

            if (!$user->partner_id) {
                return response()->json([
                    "status"       => 301,
                    "redirect_url" => route('p.teams.index')
                ]);
            }

            $data['partner_id'] = $user->partner_id;

            $redirect_url = route('p.teams.index');
        }

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status'=> 400,
                'errors'=> $validator->messages()
            ]);
        }

        if (array_key_exists("photo", $data) && !empty($data["photo"])) {
            $file = $data["photo"];
            $path = Storage::disk("public")->put(Team::FOLDER,  $file);
            $data["photo"] = $path;
        }

        Team::create($data);

        return response()->json([
            "status"       => 200,
            "message"      => "Teams Updated.",
            "redirect_url" => $redirect_url
        ]);
    }

    public function edit(Team $team, Request $request)
    {
        $isProfile = $this->isProfile($request);

        $rolesList = Team::$rolesList;

        if ($isProfile) {
            $user = Auth::user();

            if (!$user->partner_id) {
                return redirect()->route('p.teams.index');
            }

            return view('profile.teams.edit', compact(
                'rolesList',
                'team',
            ));
        }

        $partners = Partner::getPartnersName();

        return view('dashboard.teams.edit', compact(
            'team',
            'rolesList',
            'partners'
        ));
    }

    function update(Team $team, Request $request): \Illuminate\Http\JsonResponse
    {
        $isProfile = $this->isProfile($request);
        $data = $request->all();
        $redirect_url = route('d.teams.index');

        $rules = [
            'photo'       => ['nullable', self::RULES_ALLOW_TYPES],
            'name'        => 'required',
            'description' => 'nullable',
            'partner_id'  => 'required',
            'role_id'     => 'required',
        ];

        if ($isProfile) {
            $user = Auth::user();

            if (!$user->partner_id) {
                return response()->json([
                    "status"       => 301,
                    "redirect_url" => route('d.teams.index')
                ]);
            }

            $data['partner_id'] = $user->partner_id;

            $redirect_url = route('p.teams.index');
        }

        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            return response()->json([
                'status'=> 400,
                'errors'=> $validator->messages()
            ]);
        }

        if (array_key_exists("photo", $data) && !empty($data["photo"])) {
            $file = $data["photo"];
            $path = Storage::disk("public")->put(Team::FOLDER,  $file);
            $data["photo"] = $path;
        }

        $team->update($data);

        return response()->json([
            "status"       => 200,
            "message"      => "Teams Updated.",
            "redirect_url" => $redirect_url
        ]);
    }

    function destroy(Team $team, Request $request): \Illuminate\Http\RedirectResponse
    {
        $isProfile = $this->isProfile($request);

        $exists = Storage::disk(Team::FOLDER)->exists($team->photo);

        if ($exists) {
            Storage::disk(Team::FOLDER)->delete($team->photo);
        }

        $team->delete();

        if ($isProfile) {
            return redirect()->route('p.teams.index');
        }

        return redirect()->route('d.teams.index');
    }

    function statistics() {
        $_teams = Team::select('partner_id as id', Team::raw('count(partner_id) as total'))
            ->groupBy('partner_id');

        $teams = Partner::sqlAvailable(['partners.name as name', 'total'])
            ->leftJoinSub($_teams, 'teams', function (JoinClause $join) {
                $join->on('teams.id', '=', 'partners.id');
            })->get();

        return view('dashboard.teams.statistics', compact(
            'teams'
        ));
    }
}
