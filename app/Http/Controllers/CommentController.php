<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Ajouter un commentaire sur un article
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id' // Lié à un commentaire existant si c'est une réponse
        ]);

        $comment = $post->comments()->create([
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
            'user_id' => 1 // Temporaire
        ]);

        return response()->json($comment, 201);
    }

    // Supprimer un commentaire
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Commentaire supprimé'], 200);
    }
}
