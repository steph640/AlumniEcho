<?php

namespace App\Http\Controllers;

use App\Models\Temoignage;
use Illuminate\Http\Request;

class TemoignageController extends Controller
{
    public function index()
    {
        return response()->json(Temoignage::with(['utilisateur', 'promotion'])->get());
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_tem' => 'required|string|unique:temoignages',
            'contenu_tem' => 'required|string',
            'code_user' => 'required|exists:utilisateurs,code_user',
            'code_promo' => 'required|exists:promotions,code_promo'
        ]);

        $temoignage = Temoignage::create($validated);
        return response()->json($temoignage, 201);
    }
    public function show(Temoignage $temoignage)
    {
        return response()->json($temoignage->load(['utilisateur', 'promotion']));
    }
    public function update(Request $request, Temoignage $temoignage)
    {
        $validated = $request->validate([
            'contenu_tem' => 'sometimes|string',
            'code_user' => 'sometimes|exists:utilisateurs,code_user',
            'code_promo' => 'sometimes|exists:promotions,code_promo',
        ]);

        $temoignage->update($validated);
        return response()->json($temoignage);
    }
    public function destroy(Temoignage $temoignage)
    {
        $temoignage->delete();
        return response()->json(["message" => "Temoignage supprimé!"]);
    }
}
