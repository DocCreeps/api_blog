<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    // Autoriser tout utilisateur authentifié à soumettre ce formulaire
    public function authorize(): bool
    {
        return true;
    }

    // Les règles de validation strictes
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'in:draft,published',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ];
    }
}
