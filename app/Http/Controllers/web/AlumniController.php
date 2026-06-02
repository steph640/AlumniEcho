<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Filiere;
use App\Models\Promotion;
use App\Models\Souvenir;
use App\Models\Temoignage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AlumniController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $stats = [
            'souvenirs' => Souvenir::where('code_user', $user->code_user)->count(),
            'temoignages' => Temoignage::where('code_user', $user->code_user)->count(),
            'commentaires' => Commentaire::where('code_user', $user->code_user)->count(),
        ];
        $recentSouvenirs = Souvenir::where('code_user', $user->code_user)->latest()->take(3)->get();
        return view('alumni.dashboard', compact('stats', 'recentSouvenirs'));
    }

    public function profile()
    {
        $user = Auth::user();
        $filieres = Filiere::orderBy('nom_fil')->get();
        $promotions = Promotion::orderBy('nom_promo')->get();
        return view('alumni.profile', compact('user', 'filieres', 'promotions'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nom_user' => 'required|string|max:255',
            'prenom_user' => 'required|string|max:255',
            'tel_user' => 'nullable|string|max:20',
            'sexe_user' => 'nullable|in:M,F',
            'code_fil' => 'nullable|exists:filieres,code_fil',
            'code_promo' => 'nullable|exists:promotions,code_promo',
            'password_user' => 'nullable|string|min:6|confirmed',
        ]);

        // Mettre à jour les champs standards
        $user->nom_user = $validated['nom_user'];
        $user->prenom_user = $validated['prenom_user'];
        $user->tel_user = $validated['tel_user'] ?? null;
        $user->sexe_user = $validated['sexe_user'] ?? null;
        $user->code_fil = $validated['code_fil'] ?? null;
        $user->code_promo = $validated['code_promo'] ?? null;

        // Mettre à jour le mot de passe s'il est fourni
        if (!empty($validated['password_user'])) {
            $user->password = Hash::make($validated['password_user']);
        }

        $user->save();

        return redirect()->route('alumni.profile')->with('success', 'Profil mis à jour avec succès !');
    }
}
