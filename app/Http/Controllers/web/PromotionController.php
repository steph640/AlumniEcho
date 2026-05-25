<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
     public function index()
    {
        try {
            $promotions = Promotion::paginate(10);
            return view('promotions.index', compact('promotions'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du chargement : ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('promotions.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_promo' => 'required|string|max:15|unique:promotions,code_promo',
            'nom_promo' => 'required|string|max:255',
            'annee_promo' => 'required|digits:4',
            'code_fil' => 'nullable|string|max:15',
        ]);

        try {
            Promotion::create($validated);
            return redirect('/web/promotions')->with('success', 'Promotion créée avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function edit($code_promo)
    {
        try {
            $promotion = Promotion::findOrFail($code_promo);
            return view('promotions.edit', compact('promotion'));
        } catch (\Exception $e) {
            return back()->with('error', 'Promotion introuvable : ' . $e->getMessage());
        }
    }

    public function update(Request $request, $code_promo)
    {
        try {
            $promotion = Promotion::findOrFail($code_promo);

            $validated = $request->validate([
                'nom_promo' => 'sometimes|required|string|max:255',
                'annee_promo' => 'sometimes|required|digits:4',
                'code_fil' => 'sometimes|nullable|exists:filieres,code_fil',
            ]);

            $promotion->update($validated);
            return redirect('/web/promotions')->with('success', 'Promotion mise à jour!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy($code_promo)
    {
        try {
            $promotion = Promotion::findOrFail($code_promo);
            $promotion->delete();
            return redirect('/web/promotions')->with('success', 'Promotion supprimée!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
