<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Page;
use App\Models\Sheet;

class DashboardController extends Controller
{

    public function __invoke(User $user, Post $post, Page $page, Sheet $sheet)
    {
        $counts = (object) [
            'user' => User::count(),
            'post' => Post::count(),
            'page' => Page::count(),
            'sheet' => Sheet::count(),
        ];

        return view('dashboard.home.index', compact('counts'));
    }
}
