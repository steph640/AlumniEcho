<?php

namespace App\Http\Controllers;

use App\Models\Souvenir;
use Illuminate\Http\Request;

class SouvenirController extends Controller
{
    public function index()
    {
        return response()->json(Souvenir::with(['utilisateur','promotion'])->get());
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_souv'=>'required|string|unique:souvenirs',
            'titre_souv'=>'required|string',
            'description_souv'=>'required|string',
            'url_photo_souv' => 'sometimes|string|nullable',
            'code_user'=>'required|exists:utilisateurs,code_user',
            'code_promo'=>'required|exists:promotions,code_promo'
        ]);

        $souvenir = Souvenir::create($validated);
        return response()->json($souvenir,201);
    }
    public function show(Souvenir $souvenir)
    {
        return response()->json($souvenir->load(['utilisateur','promotion']));
    }
    public function update(Request $request,Souvenir $souvenir)
    {
        $validated = $request->validate([
            'titre_souv' => 'sometimes|string',
            'description_souv' => 'sometimes|string',
            'url_photo_souv' => 'sometimes|string|nullable',
            'code_user' => 'sometimes|exists:utilisateurs,code_user',
            'code_promo' => 'sometimes|exists:promotions,code_promo',
        ]);

        $souvenir->update($validated);
        return response()->json($souvenir);
    }
    public function destroy(Souvenir $souvenir)
    {
        $souvenir->delete();
        return response()->json(["message"=>"Souvenir supprimé!"]);
    }
}
