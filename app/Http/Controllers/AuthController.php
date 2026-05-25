<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Affiche le formulaire de register
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Traite la connexion utilisateur (API)
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_user' => 'required|string',
            'password_user' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $utilisateur = Utilisateur::where('login_user', $request->login_user)->first();

        if (!$utilisateur || !Hash::check($request->password_user, $utilisateur->password_user)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        if ($utilisateur->etat_user != 'actif') {
            return response()->json(['message' => 'Compte désactivé'], 403);
        }

        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'utilisateur' => $utilisateur
        ]);
    }

    /**
     * Traite la connexion web
     */
    public function loginWeb(Request $request)
    {
        $request->validate([
            'login_user' => 'required|string',
            'password_user' => 'required|string',
        ]);

        $utilisateur = Utilisateur::where('login_user', $request->login_user)->first();

        if (!$utilisateur || !Hash::check($request->password_user, $utilisateur->password_user)) {
            return back()->with('error', 'Identifiants incorrects');
        }

        if ($utilisateur->etat_user != 'actif') {
            return back()->with('error', 'Compte désactivé');
        }

        Auth::guard('web')->login($utilisateur);

        return redirect()->intended($this->redirectPath($utilisateur->role_user));
    }

    /**
     * Traite l'enregistrement utilisateur (API)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_user' => 'required|string|max:100',
            'prenom_user' => 'required|string|max:100',
            'login_user' => 'required|string|unique:utilisateurs,login_user',
            'password_user' => 'required|string|min:6',
            'tel_user' => 'nullable|string',
            'sexe_user' => 'nullable|in:M,F',
            'code_promo' => 'nullable|exists:promotions,code_promo',
            'code_fil' => 'nullable|exists:filieres,code_fil',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $utilisateur = Utilisateur::create([
            'code_user' => $this->generateCodeUser(),
            'nom_user' => $request->nom_user,
            'prenom_user' => $request->prenom_user,
            'login_user' => $request->login_user,
            'password_user' => Hash::make($request->password_user),
            'tel_user' => $request->tel_user,
            'sexe_user' => $request->sexe_user,
            'role_user' => 'alumni', // Par défaut alumni
            'etat_user' => 'actif',
            'code_promo' => $request->code_promo,
            'code_fil' => $request->code_fil,
        ]);

        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'token' => $token,
            'utilisateur' => $utilisateur
        ], 201);
    }

    /**
     * Traite l'enregistrement web
     */
    public function registerWeb(Request $request)
    {
        $request->validate([
            'nom_user' => 'required|string|max:100',
            'prenom_user' => 'required|string|max:100',
            'login_user' => 'required|string|unique:utilisateurs,login_user',
            'password_user' => 'required|string|min:6|confirmed',
            'tel_user' => 'nullable|string',
            'sexe_user' => 'nullable|in:M,F',
            'code_promo' => 'nullable|exists:promotions,code_promo',
            'code_fil' => 'nullable|exists:filieres,code_fil',
        ]);

        $utilisateur = Utilisateur::create([
            'code_user' => $this->generateCodeUser(),
            'nom_user' => $request->nom_user,
            'prenom_user' => $request->prenom_user,
            'login_user' => $request->login_user,
            'password_user' => Hash::make($request->password_user),
            'tel_user' => $request->tel_user,
            'sexe_user' => $request->sexe_user,
            'role_user' => 'alumni',
            'etat_user' => 'actif',
            'code_promo' => $request->code_promo,
            'code_fil' => $request->code_fil,
        ]);

        Auth::guard('web')->login($utilisateur);

        return redirect()->intended('/alumni/dashboard');
    }

    /**
     * Déconnexion utilisateur
     */
    public function logout(Request $request)
    {
        if ($request->expectsJson()) {
            // API logout
            Auth::guard('sanctum')->user()?->currentAccessToken()?->delete();
            return response()->json(['message' => 'Déconnexion réussie']);
        }

        // Web logout
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Génère un code utilisateur unique
     */
    private function generateCodeUser()
    {
        do {
            $code = 'USR' . strtoupper(substr(uniqid(), -6));
        } while (Utilisateur::where('code_user', $code)->exists());

        return $code;
    }

    /**
     * Détermine le chemin de redirection selon le rôle
     */
    private function redirectPath($role)
    {
        return match ($role) {
            'admin' => '/admin/dashboard',
            'alumni' => '/alumni/dashboard',
            default => '/visiteur/dashboard',
        };
    }
}

