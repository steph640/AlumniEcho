<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    public function index()
    {
        try {
            $filieres = Filiere::paginate(10);
            return view('filieres.index', compact('filieres'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('filieres.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_fil' => 'required|string|max:15|unique:filieres,code_fil',
            'nom_fil' => 'required|string|max:255',
            'description_fil' => 'nullable|string',
        ]);

        try {
            Filiere::create($validated);
            return redirect('/web/filieres')->with('success', 'Filière créée!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function edit($code_fil)
    {
        try {
            $filiere = Filiere::findOrFail($code_fil);
            return view('filieres.edit', compact('filiere'));
        } catch (\Exception $e) {
            return back()->with('error', 'Filière introuvable : ' . $e->getMessage());
        }
    }

    public function update(Request $request, $code_fil)
    {
        try {
            $validated = $request->validate([
                'nom_fil' => 'sometimes|required|string|max:255',
                'description_fil' => 'sometimes|nullable|string',
            ]);

            $filiere = Filiere::findOrFail($code_fil);
            $filiere->update($validated);
            return redirect('/web/filieres')->with('success', 'Filière mise à jour!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy($code_fil)
    {
        try {
            $filiere = Filiere::findOrFail($code_fil);
            $filiere->delete();
            return redirect('/web/filieres')->with('success', 'Filière supprimée!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
