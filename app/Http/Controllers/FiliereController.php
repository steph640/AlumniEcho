<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filiere;

class FiliereController extends Controller
{
    public function index()
    {
        return response()->json(Filiere::with(['utilisateurs', 'promotions'])->get());
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_fil' => 'required|string|unique:filieres',
            'nom_fil' => 'required|string',
            'description_fil' => 'sometimes|string|nullable',
        ]);

        $filiere = Filiere::create($validated);
        return response()->json($filiere, 201);
    }
    public function show(Filiere $filiere)
    {
        return response()->json($filiere->load(['utilisateurs', 'promotions']));
    }
    public function update(Request $request, Filiere $filiere)
    {
        $validated = $request->validate([
            'nom_fil' => 'sometimes|string',
            'description_fil' => 'sometimes|string|nullable',
        ]);

        $filiere->update($validated);
        return response()->json($filiere);
    }
    public function destroy(Filiere $filiere)
    {
        $filiere->delete();
        return response()->json(["message" => "Filiere supprimé!"]);
    }
}
