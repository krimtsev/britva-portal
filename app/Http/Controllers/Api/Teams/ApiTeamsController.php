<?php

namespace  App\Http\Controllers\Api\Teams;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Team;
use App\Utils\Utils;
use Illuminate\Http\Request;

class ApiTeamsController extends Controller
{
    public function getTeamsList() {
        $teams = Team::orderBy('name', 'ASC')
            ->whereNull('deleted_at')
            ->get();

        $partners = Partner::getPartnersName();
        $url = env('PUBLIC_URL', '');

        $temp = [];

        foreach ($teams as $team) {
            $temp[$team->partner_id][] = [
                "id"          => $team->id,
                "name"        => $team->name,
                "description" => $team->description,
                "photo"       => Utils::joinPath($url, 'storage', $team->photo),
                "role"        => Team::$rolesList[$team->role_id],
                "partner"     => $partners[$team->partner_id],
                "updated_at"  => $team->updated_at,
            ];
        }

        return $temp;
    }

    public function getTeamsByPartnersId(Request $request) {
        $partner_id = $request->partner_id;

        $teams = Team::orderBy('name', 'ASC')
            ->where("partner_id", $partner_id)
            ->whereNull('deleted_at')
            ->get();

        $partners = Partner::getPartnersName();
        $url = env('PUBLIC_URL', '');

        $temp = [];

        foreach ($teams as $team) {
            $temp[] = [
                "id"          => $team->id,
                "name"        => $team->name,
                "description" => $team->description,
                "photo"       => Utils::joinPath($url, 'storage', $team->photo),
                "role"        => Team::$rolesList[$team->role_id],
                "partner"     => $partners[$team->partner_id],
                "updated_at"  => $team->updated_at,
            ];
        }

        return $temp;
    }

    public function getTeamsRoles() {
        return Team::$rolesList;
    }
}
