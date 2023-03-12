<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;

class ShowController extends Controller
{

    public function __invoke(Post $post)
    {
        return view('dashboard.post.show', compact('post'));
    }

}
