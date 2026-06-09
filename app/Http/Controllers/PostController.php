<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;  
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $posts = Post::with(['user', 'category', 'tags'])
            ->withCount(['likes', 'comments'])
            ->where('status', 'published')
            ->latest()
            ->paginate(10);

        return response()->json($posts, 200);
    }

    public function store(StorePostRequest $request)
    {

        $post = $request->user()->posts()->create($request->validated());

        if (!empty($request->tags)) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags'), 201);
    }

    public function show(Post $post)
    {
        return response()->json($post->load(['user', 'category', 'tags', 'comments.replies']), 200);
    }

    // Utilisation de UpdatePostRequest
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post->update($request->validated());

        if (isset($request->tags)) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load('tags'), 200);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();
        return response()->json(['message' => 'Article archivé (Soft Delete)'], 200);
    }
}
