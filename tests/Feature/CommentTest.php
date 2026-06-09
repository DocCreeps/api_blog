<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un utilisateur connecté peut commenter un article', function () {
    $user = User::factory()->create();
    $category = Category::create(['name' => 'Actu']);
    $post = $user->posts()->create([
        'title' => 'Mon super article',
        'content' => 'Contenu',
        'category_id' => $category->id
    ]);

    $response = $this->actingAs($user)->postJson("/api/posts/{$post->slug}/comments", [
        'content' => 'Ceci est un commentaire valide.'
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('comments', [
        'content' => 'Ceci est un commentaire valide.',
        'post_id' => $post->id,
        'user_id' => $user->id
    ]);
});

test('un commentaire est rejeté s il fait moins de 2 caractères', function () {
    $user = User::factory()->create();
    $category = Category::create(['name' => 'Actu']);
    $post = $user->posts()->create(['title' => 'Titre', 'content' => 'Contenu', 'category_id' => $category->id]);

    $response = $this->actingAs($user)->postJson("/api/posts/{$post->slug}/comments", [
        'content' => 'A' // Trop court, la Form Request doit bloquer
    ]);

    $response->assertStatus(422); // Unprocessable Entity
    $response->assertJsonValidationErrors(['content']);
});

test('un utilisateur peut répondre à un commentaire existant', function () {
    $user = User::factory()->create();
    $category = Category::create(['name' => 'Actu']);
    $post = $user->posts()->create(['title' => 'Titre', 'content' => 'Contenu', 'category_id' => $category->id]);

    // Commentaire parent
    $parentComment = $post->comments()->create([
        'content' => 'Premier commentaire',
        'user_id' => $user->id
    ]);

    // Envoi de la réponse (Child)
    $response = $this->actingAs($user)->postJson("/api/posts/{$post->slug}/comments", [
        'content' => 'Ma réponse imbriquée',
        'parent_id' => $parentComment->id
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('comments', [
        'content' => 'Ma réponse imbriquée',
        'parent_id' => $parentComment->id // Vérifie la récursivité
    ]);
});
