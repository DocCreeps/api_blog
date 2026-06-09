<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // La sécurité d'accès est gérée globalement par Sanctum
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|min:2|max:1000',
            'parent_id' => 'nullable|exists:comments,id' // Permet de vérifier si le commentaire auquel on répond existe vraiment
        ];
    }
}
