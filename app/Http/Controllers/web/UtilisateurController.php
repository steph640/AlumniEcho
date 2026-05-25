<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Promotion;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    private function generateCode(): string
    {
        do {
            $code = 'USR' . strtoupper(substr(uniqid(), -7));
        } while (Utilisateur::where('code_user', $code)->exists());
        return $code;
    }

    public function index()
    {
        $utilisateurs_list = Utilisateur::paginate(15);
        return view('utilisateurs.index', compact('utilisateurs_list'));
    }

    public function create()
    {
        $promotions = Promotion::all();
        $filieres = Filiere::all();
        return view('utilisateurs.create', compact('promotions', 'filieres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_user'      => 'required|string|max:100',
            'prenom_user'   => 'required|string|max:100',
            'login_user'    => 'required|string|max:50|unique:utilisateurs,login_user',
            'password_user' => 'required|string|min:6',
            'tel_user'      => 'nullable|string|max:20',
            'sexe_user'     => 'required|in:M,F',
            'role_user'     => 'required|in:admin,alumni,visiteur',
            'etat_user'     => 'required|in:actif,inactif',
            'code_promo'    => 'nullable|exists:promotions,code_promo',
            'code_fil'      => 'nullable|exists:filieres,code_fil',
        ]);

        $validated['code_user'] = $this->generateCode();
        $validated['password_user'] = Hash::make($validated['password_user']);

        Utilisateur::create($validated);
        return redirect(route('admin.utilisateurs.index'))->with('success', 'Utilisateur créé avec succès !');
    }

    public function show($code_user)
    {
        $utilisateur = Utilisateur::findOrFail($code_user);
        return view('utilisateurs.show', compact('utilisateur'));
    }

    public function edit($code_user)
    {
        $utilisateur = Utilisateur::findOrFail($code_user);
        $promotions = Promotion::all();
        $filieres = Filiere::all();
        return view('utilisateurs.edit', compact('utilisateur', 'promotions', 'filieres'));
    }

    public function update(Request $request, $code_user)
    {
        $utilisateur = Utilisateur::findOrFail($code_user);

        $validated = $request->validate([
            'nom_user'    => 'required|string|max:100',
            'prenom_user' => 'required|string|max:100',
            'tel_user'    => 'nullable|string|max:20',
            'sexe_user'   => 'required|in:M,F',
            'role_user'   => 'required|in:admin,alumni,visiteur',
            'etat_user'   => 'required|in:actif,inactif',
            'code_promo'  => 'nullable|exists:promotions,code_promo',
            'code_fil'    => 'nullable|exists:filieres,code_fil',
        ]);

        if ($request->filled('password_user')) {
            $validated['password_user'] = Hash::make($request->password_user);
        }

        $utilisateur->update($validated);
        return redirect(route('admin.utilisateurs.index'))->with('success', 'Utilisateur mis à jour !');
    }

    public function destroy($code_user)
    {
        $utilisateur = Utilisateur::findOrFail($code_user);
        $utilisateur->delete();
        return redirect(route('admin.utilisateurs.index'))->with('success', 'Utilisateur supprimé !');
    }
}
