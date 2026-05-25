<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Souvenir;
use App\Models\Temoignage;
use App\Models\Utilisateur;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users'        => Utilisateur::count(),
            'alumnis'      => Utilisateur::where('role_user', 'alumni')->count(),
            'visiteurs'    => Utilisateur::where('role_user', 'visiteur')->count(),
            'souvenirs'    => Souvenir::count(),
            'temoignages'  => Temoignage::count(),
            'commentaires' => Commentaire::count(),
        ];
        $recentUsers = Utilisateur::latest()->take(8)->get();
        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
