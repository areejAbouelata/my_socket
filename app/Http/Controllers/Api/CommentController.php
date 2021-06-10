<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentApiStore;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentApiStore $request)
    {
        $post = Post::find($request->post_id);
        $comment = $post->comments()->create(['body' => $request->comment, 'user_id' => auth()->user()->id]);

        if ($comment) {
            broadcast(new CommentEvent($post->user));
            return response()->json(['status' => true, 'comment' => $comment]);
        }
        return response()->json(['status' => false]);
    }

    public function index($post_id)
    {
        $post = Post::find($post_id);
        if ($post) {
            $comment = $post->comments;
            return response()->json(['status' => true, 'comment' => $comment]);
        }
        return response()->json(['status' => false]);

    }
}
