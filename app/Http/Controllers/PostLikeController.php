<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostLikeController extends Controller
{
    public function toggle(Post $post)
    {
        $userId = auth()->id();
        $like = $post->likes()->where('user_id', $userId)->first();
        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Like retiré de l\'article'], 200);
        }
        $post->likes()->create(['user_id' => $userId]);
        return response()->json(['message' => 'Article liké'], 201);
    }
}
