<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Souvenir;
use App\Models\Temoignage;
use App\Models\Utilisateur;

class VisiteurController extends Controller
{
    public function dashboard()
    {
        $recentSouvenirs  = Souvenir::with('promotion')->latest()->take(5)->get();
        $recentTemoignages = Temoignage::with(['utilisateur','promotion'])
                              ->where('valide_tem', true)->latest()->take(4)->get();
        $stats = [
            'souvenirs'   => Souvenir::count(),
            'temoignages' => Temoignage::where('valide_tem', true)->count(),
            'alumnis'     => Utilisateur::where('role_user', 'alumni')->count(),
        ];
        return view('visiteur.dashboard', compact('recentSouvenirs', 'recentTemoignages', 'stats'));
    }
}
