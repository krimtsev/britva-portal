<?php

namespace  App\Http\Controllers\Api\Partners;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Team;
use Illuminate\Http\Request;

class ApiPartnersController extends Controller
{
    public function getPartnersList() {
        $partners = Partner::getPartnersName();

        return $partners;
    }
}
