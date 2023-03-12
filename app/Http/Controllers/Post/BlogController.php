<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class BlogController extends Controller
{

    public function __invoke()
    {
        $posts = Post::where('is_published', '=', 1)->orderBy('id', 'DESC')->paginate(10);

        return view('post.index', compact('posts'));
    }

}
