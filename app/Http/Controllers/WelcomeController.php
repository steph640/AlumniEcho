<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Souvenir;
use App\Models\Temoignage;
use App\Models\Utilisateur;

class WelcomeController extends Controller
{
    public function index()
    {
        $souvenirs   = Souvenir::with(['promotion'])->latest()->take(8)->get();
        $temoignages = Temoignage::with(['utilisateur', 'promotion'])
                        ->where('valide_tem', true)->latest()->take(6)->get();
        $promotions  = Promotion::latest()->take(8)->get();

        return view('welcome', compact('souvenirs', 'temoignages', 'promotions'));
    }
}
