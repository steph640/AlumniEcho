<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Filiere;
use App\Models\Promotion;
use App\Models\Souvenir;
use App\Models\Temoignage;
use Illuminate\Support\Facades\Auth;

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
        return view('alumni.profile', compact('user'));
    }
}
