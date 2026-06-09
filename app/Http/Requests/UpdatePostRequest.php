<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // La sécurité liée à la possession de l'article sera gérée par la Policy
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'status' => 'sometimes|in:draft,published',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ];
    }
}
