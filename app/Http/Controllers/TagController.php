<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return response()->json(Tag::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name'
        ]);

        $tag = Tag::create($validated);
        return response()->json($tag, 201);
    }

    public function show(Tag $tag)
    {
        return response()->json($tag->load('posts'), 200);
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name,' . $tag->id
        ]);

        $tag->update($validated);
        return response()->json($tag, 200);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json(['message' => 'Tag supprimé'], 200);
    }
}
