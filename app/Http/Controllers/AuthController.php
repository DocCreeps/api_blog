<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Inscription (Register)
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed' // Requiert 'password_confirmation'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']), // Chiffrement du mot de passe
        ]);

        // Création du Token de sécurité
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Connexion (Login)
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // Vérification de l'email
        $user = User::where('email', $fields['email'])->first();

        // Vérification du mot de passe
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects.'], 401);
        }

        // Génération d'un nouveau Token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    // Déconnexion (Logout)
    public function logout(Request $request)
    {
        // Supprime le token actuel utilisé pour la requête
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie (Token révoqué)'], 200);
    }
}
