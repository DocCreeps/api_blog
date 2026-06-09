<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Utilise RefreshDatabase pour vider la base en mémoire entre chaque test
uses(RefreshDatabase::class);

test('un utilisateur anonyme ne peut pas créer un article', function () {
    $response = $this->postJson('/api/posts', [
        'title' => 'Mon titre',
        'content' => 'Mon contenu',
    ]);

    $response->assertStatus(401); // Unauthorized
});

test('un utilisateur connecté peut créer un article et générer son slug', function () {
    // 1. Arrange (Préparation)
    $user = User::factory()->create();
    $category = Category::create(['name' => 'Design']);
    $tag = Tag::create(['name' => 'CSS']);

    // 2. Act (Action)
    // actingAs simule la connexion via Sanctum
    $response = $this->actingAs($user)->postJson('/api/posts', [
        'title' => 'Nouvel Article Incroyable !',
        'content' => 'Contenu de test pour Pest.',
        'category_id' => $category->id,
        'status' => 'published',
        'tags' => [$tag->id]
    ]);

    // 3. Assert (Vérifications)
    $response->assertStatus(201);

    // On vérifie que l'article possède le slug attendu en BDD
    $this->assertDatabaseHas('posts', [
        'title' => 'Nouvel Article Incroyable !',
        'slug' => 'nouvel-article-incroyable',
        'user_id' => $user->id
    ]);

    // On vérifie que la table pivot est remplie
    $this->assertDatabaseHas('post_tag', [
        'tag_id' => $tag->id
    ]);
});

test('un utilisateur ne peut pas modifier l article d un autre', function () {
    $auteur = User::factory()->create();
    $pirate = User::factory()->create();
    $category = Category::create(['name' => 'Backend']);

    $post = $auteur->posts()->create([
        'title' => 'Article Original',
        'content' => 'Texte de base',
        'category_id' => $category->id
    ]);

    // Le pirate tente de modifier l'article
    $response = $this->actingAs($pirate)->putJson("/api/posts/{$post->slug}", [
        'title' => 'Titre piraté'
    ]);

    $response->assertStatus(403); // Forbidden (Bloqué par la Policy)
});
