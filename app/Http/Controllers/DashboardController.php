<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Page;
use App\Models\Sheet;
use App\Models\Digest;

class DashboardController extends Controller
{

    public function __invoke()
    {
        $counts = (object) [
            'user' => User::count(),
            'post' => Post::count(),
            'page' => Page::count(),
            'sheet' => Sheet::count(),
            'digest' => Digest::count(),
        ];

        return view('dashboard.user.index', compact('counts'));
    }
}
