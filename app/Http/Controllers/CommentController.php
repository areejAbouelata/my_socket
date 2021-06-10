<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Http\Requests\CommentStoreRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create(CommentStoreRequest $request)
    {
        $post = Post::find($request->post_id);
        $comment = $post->comments()->create(['body' => $request->comment, 'user_id' => auth()->user()->id]);

        if ($comment) {
            broadcast(new CommentEvent($post->user));
            return response()->json(['message' => 'success', 'comment' => $comment]);
        }
        return response()->json(['message' => 'failed']);
    }
}
