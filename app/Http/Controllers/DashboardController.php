<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Page;
use App\Models\Sheet;
use App\Models\Digest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __invoke()
    {
        $counts = [];
        if (Auth::user()->isSysAdmin()) {
            $counts = [
                'user' => User::count(),
                'post' => Post::count(),
                'page' => Page::count(),
                'sheet' => Sheet::count(),
                'digest' => Digest::count(),
            ];
        }

        return view('dashboard.home.index', compact('counts'));
    }
}
