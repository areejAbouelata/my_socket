<?php

namespace App\Http\Controllers;

use App\Events\PostEvent;
use App\Helpers\Helper;
use App\Http\Requests\LikePostRequest;
use App\Http\Requests\StorePost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    public function create()
    {
        $list = Helper::notifications();
        return view('posts.create', compact('list'));
    }

    public function store(StorePost $request)
    {
        $post = auth()->user()->posts()->create($request->all());
        if ($request->hasFile('photo')) {
//            return $request->all() ;

            $image = $request->file('photo');
            $image_name = time() . '.' . $image->extension();
            $post->files()->create(['file' => $image_name]);
            $request->file('photo')->storeAs('public/images/posts/', $image_name);
//            ('public/images/posts/', $image_name);
        }
        Redis::set($post->id, auth()->user()->name . 'add new post');
        event(new PostEvent(auth()->user()));

        return redirect()->back()->with('message', 'your post added successfully');
    }

    public function like(LikePostRequest $request)
    {
        $post = Post::find($request->post_id);
        $old_like = auth()->user()->post_like($post->id);
        if ($old_like) {
            $old_like->delete();
            return response()->json(['message' => 'un_liked']);
        }
        $like = $post->likes()->create(['is_liked' => 1, 'user_id' => auth()->user()->id, 'type' => 'post']);
        if (!$like) {
            return response()->json(['message' => 'error']);
        }
        return response()->json(['message' => 'liked']);
    }
}
