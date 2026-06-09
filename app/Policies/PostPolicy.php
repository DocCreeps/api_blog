<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Détermine si l'utilisateur peut modifier l'article.
     */
    public function update(User $user, Post $post): bool
    {
        // Vrai uniquement si l'id de l'auteur de l'article correspond à l'id du connecté
        return $user->id === $post->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer l'article.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}
