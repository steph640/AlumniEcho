<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commentaire;

class CommentaireController extends Controller
{
    public function index()
    {
        return response()->json(Commentaire::with(['utilisateur','promotion'])->get());
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_com'=>'required|string|unique:commentaires',
            'contenu_com'=>'required|string',
            'code_user'=>'required|exists:utilisateurs,code_user',
            'code_tem'=>'required|exists:temoignages,code_tem',
        ]);

        $commentaire = Commentaire::create($validated);
        return response()->json($commentaire,201);
    }
    public function show(Commentaire $commentaire)
    {
        return response()->json($commentaire->load(['utilisateur','temoignage']));
    }
    public function update(Request $request,Commentaire $commentaire)
    {
        $validated = $request->validate([
            'contenu_com' => 'sometimes|string',
            'code_user' => 'sometimes|exists:utilisateurs,code_user',
            'code_souv' => 'sometimes|exists:souvenirs,code_souv',
        ]);

        $commentaire->update($validated);
        return response()->json($commentaire);
    }
    public function destroy(Commentaire $commentaire)
    {
        $commentaire->delete();
        return response()->json(["message"=>"Commentaire supprimé!"]);
    }
}
