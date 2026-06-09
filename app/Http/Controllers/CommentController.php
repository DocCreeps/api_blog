<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Ajouter un commentaire ou une réponse sur un article
     */
    public function store(StoreCommentRequest $request, Post $post)
    {
        // Les données injectées ici sont déjà validées
        $comment = $post->comments()->create([
            'content' => $request->validated()['content'],
            'parent_id' => $request->validated()['parent_id'] ?? null,
            'user_id' => $request->user()->id
        ]);

        return response()->json($comment->load('user'), 201);
    }

    /**
     * Supprimer un commentaire
     */
    public function destroy(Comment $comment)
    {
        // Applique le filtre de la CommentPolicy (méthode delete)
        $this->authorize('delete', $comment);

        $comment->delete(); // Soft Delete actif ici aussi

        return response()->json(['message' => 'Commentaire supprimé avec succès'], 200);
    }
}
