<?php

namespace App\Http\Controllers;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    public function index()
    {
        return response()->json(Utilisateur::with(['souvenirs', 'temoignages', 'commentaires', 'promotion', 'filiere'])->get());
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_user' => 'required|string|unique:utilisateurs',
            'nom_user' => 'required|string',
            'prenom_user' => 'required|string',
            'login_user' => 'required|string|unique:utilisateurs',
            'password_user' => 'required|string|min:8',
            'tel_user' => 'required|string',
            'sexe_user' => 'required|in:M,F',
            'role_user' => 'required|in:admin,alumni,visiteur',
            'etat_user' => 'required|in:actif,inactif,bloquer',
        ]);
        //validation conditionnelle selon le rôle
        if ($validated['role_user'] === 'alumni') {
            $extra = $request->validate([
                'code_promo' => 'required|exists:promotions,code_promo',
                'code_fil' => 'required|exists:filieres,code_fil',
            ]);
            $validated = array_merge($validated, $extra);
        } else {
            $extra = $request->validate([
                'code_promo' => 'sometimes|exists:promotions,code_promo',
                'code_fil' => 'sometimes|exists:filieres,code_fil',
            ]);
            $validated = array_merge($validated, $extra);
        }


        $validated['password_user'] = bcrypt($validated['password_user']);

        $utilisateur = Utilisateur::create($validated);
        return response()->json($utilisateur, 201);
    }
    public function show(Utilisateur $utilisateur)
    {
        return response()->json($utilisateur->load(['souvenirs', 'temoignages', 'commentaires', 'promotion', 'filiere']));
    }
    public function update(Request $request, Utilisateur $utilisateur)
    {
        $validated = $request->validate([
            'nom_user' => 'sometimes|string',
            'prenom_user' => 'sometimes|string',
            'login_user' => 'sometimes|string|unique:utilisateurs,login_user,' . $utilisateur->code_user . ',code_user',
            'password_user' => 'sometimes|string|min:8',
            'tel_user' => 'sometimes|string',
            'sexe_user' => 'sometimes|in:M,F',
            'role_user' => 'sometimes|in:admin,alumni,visiteur',
            'etat_user' => 'sometimes|in:actif,inactif,bloquer',
        ]);

        $role = $request->input('role_user', $utilisateur->role_user);
        if ($role === 'alumni') {
            $extra = $request->validate([
                'code_promo' => 'required|exists:promotions,code_promo',
                'code_fil' => 'required|exists:filieres,code_fil',
            ]);
            $validated = array_merge($validated, $extra);
        } else {
            $extra = $request->validate([
                'code_promo' => 'sometimes|exists:promotions,code_promo',
                'code_fil' => 'sometimes|exists:filieres,code_fil',
            ]);
            $validated = array_merge($validated, $extra);
        }

        if (isset($validated['password_user'])) {
            $validated['password_user'] = bcrypt($validated['password_user']);
        }

        $utilisateur->update($validated);
        return response()->json($utilisateur);
    }
    public function destroy(Utilisateur $utilisateur)
    {
        $utilisateur->delete();
        return response()->json(['message' => 'Utilisateur supprimé']);
    }
}
