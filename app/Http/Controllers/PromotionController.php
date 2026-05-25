<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        return response()->json(Promotion::with(['filiere','utilisateurs','souvenirs','temoignages'])->get());
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_promo'=>'required|string|unique:promotions',
            'nom_promo'=>'required|string',
            'annee_promo'=>'required|integer',
            'code_fil'=>'required|exists:filieres,code_fil',
        ]);

        $promotion = Promotion::create($validated);
        return response()->json($promotion,201);
    }
    public function show(Promotion $promotion)
    {
        return response()->json($promotion->load(['filiere','utilisateurs','souvenirs','temoignages']));
    }
    public function update(Request $request,Promotion $promotion)
    {
        $validated = $request->validate([
            'nom_promo'=>'sometimes|string',
            'annee_promo'=>'sometimes|integer',
            'code_fil'=>'sometimes|exists:filieres,code_fil',
        ]);

        $promotion->update($validated);
        return response()->json($promotion);
    }
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return response()->json(["message"=>"Promotion supprimée!"]);
    }
}
