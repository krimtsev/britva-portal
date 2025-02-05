<?php

namespace App\View\Components;

use App\Providers\RouteServiceProvider;
use Illuminate\View\Component;

class AdminLayout extends Component
{
    public function render()
    {
        list(
            "isProfile" => $isProfile,
            "isDashboard" => $isDashboard
        ) = RouteServiceProvider::getAdminTypeView();

        $partner = env('PARTNER_NAME', '');
        $isBritvaPartner = $partner == "britva";

        return view("layouts.admin", compact(
            "isProfile",
            "isDashboard",
            "isBritvaPartner"
        ));
    }
}
