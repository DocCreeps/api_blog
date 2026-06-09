<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentLikeController extends Controller
{
    public function toggle(Comment $comment)
    {
        $userId = 1; // Temporaire
        $like = $comment->likes()->where('user_id', $userId)->first();
        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Like retiré du commentaire'], 200);
        }
        $comment->likes()->create(['user_id' => $userId]);
        return response()->json(['message' => 'Commentaire liké'], 201);
    }
}
