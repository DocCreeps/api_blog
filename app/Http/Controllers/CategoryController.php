<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::withCount('posts')->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name'
        ]);

        $category = Category::create($validated);
        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        return response()->json($category->load('posts'), 200);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id
        ]);

        $category->update($validated);
        return response()->json($category, 200);
    }

    public function destroy(Category $category)
    {
        $category->delete(); // Soft Delete intégré
        return response()->json(['message' => 'Catégorie supprimée (Soft Delete)'], 200);
    }
}
