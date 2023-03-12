<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Post;
use App\Config\Constants;
use File;

class UpdateController extends Controller
{

    public function __invoke(Post $post, Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'is_published' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_image' => 'nullable|string'
        ]);

        if(array_key_exists('remove_image', $data)) {
            $data['image'] = null;

            if(File::exists($post->image)){
                File::delete($post->image);
            }
        }

        if(array_key_exists('image', $data) && !is_null($data['image'])) {
            $imageName = md5(uniqid() . microtime()).'.'.$request->image->extension();
            $data['image']->move(public_path(Constants::IMAGES_PATH), $imageName);
            $data['image'] = Constants::IMAGES_PATH . $imageName;

            if(File::exists($post->image)){
                File::delete($post->image);
            }
        }

        $post->update($data);

        return redirect()->route('d.post.index', $post->id);
    }

}
