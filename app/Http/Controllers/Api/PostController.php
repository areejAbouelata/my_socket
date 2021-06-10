<?php

namespace App\Http\Controllers\Api;

use App\Events\PostEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApiPost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tymon\JWTAuth\JWTAuth;

class PostController extends Controller
{
    public function store(StoreApiPost $request)
    {
        $post = auth()->user()->posts()->create($request->all());
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $image_name = time() . '.' . $image->extension();
            $post->files()->create(['file' => $image_name]);
            $request->file('photo')->storeAs('public/images/posts/', $image_name);
//            ('public/images/posts/', $image_name);
        }
        Redis::set($post->id, auth()->user()->name . 'add new post');
        event(new PostEvent(auth()->user()));
        $post->file = $post->files->first() ? URL::asset(Storage::url('/images/posts/'.$post->files->first()->file))  : null ;
        return response()->json(['status' => true, 'post' => $post]);

    }

    public function index()
    {
        $posts = Post::with('comments')->get() ;
        return response()->json(['status' => true, 'posts' => $posts]);

    }

   
}
