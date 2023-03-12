<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;

class IndexController extends Controller
{

    public function __invoke()
    {
        $posts = Post::orderBy('id', 'DESC')->paginate(30);

        return view('dashboard.post.index', compact('posts'));
    }
}
