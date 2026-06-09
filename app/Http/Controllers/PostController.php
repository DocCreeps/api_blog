<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // On charge les relations essentielles + le compte des likes et commentaires
        $posts = Post::with(['user', 'category', 'tags'])
            ->withCount(['likes', 'comments'])
            ->where('status', 'published')
            ->latest()
            ->paginate(10); // Pagination indispensable en production

        return response()->json($posts, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'in:draft,published',
            'tags' => 'array', // Doit être un tableau d'IDs de tags (ex: [1, 3])
            'tags.*' => 'exists:tags,id'
        ]);

        // On injecte temporairement l'user_id à 1
        $validated['user_id'] = 1;

        $post = Post::create($validated);

        // Synchronisation des tags dans la table pivot
        if (!empty($request->tags)) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags'), 201);
    }

    public function show(Post $post)
    {
        return response()->json($post->load(['user', 'category', 'tags', 'comments.replies']), 200);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'category_id' => 'exists:categories,id',
            'status' => 'in:draft,published',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ]);

        $post->update($validated);

        if (isset($request->tags)) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags'), 200);
    }

    public function destroy(Post $post)
    {
        $post->delete(); // Soft delete l'article
        return response()->json(['message' => 'Article archivé (Soft Delete)'], 200);
    }
}
