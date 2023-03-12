<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;

class EditController extends Controller
{

    public function __invoke(Post $post)
    {
        return view('dashboard.post.edit', compact('post'));
    }

}
