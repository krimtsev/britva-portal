<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Digest;

class MainLayout extends Component
{
    public function render()
    {
        $digests = Digest::where('is_published', '=', 1)->orderBy('id', 'DESC')->take(3)->get();

        $partner = env('PARTNER_NAME', '');
        $isBritvaPartner = $partner == "britva";

        return view('layouts.main', compact(
            'digests',
            'isBritvaPartner'
        ));
    }
}
