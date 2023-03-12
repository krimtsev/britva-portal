<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;

class StoreController extends Controller
{

    public function __invoke(Request $request)
    {
        $data = request()->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if(array_key_exists('image', $data)) {
            $imageName = md5(uniqid() . microtime()).'.'.$request->image->extension();
            $data['image']->move(public_path(Constants::IMAGES_PATH), $imageName);
            $data['image'] = Constants::IMAGES_PATH . $imageName;
        }

        $data['user_id'] = $request->user()->id;

        Post::create($data);

        return redirect()->route('d.post.index');
    }

}
