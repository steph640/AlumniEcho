<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Temoignage;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemoignageController extends Controller
{
    private function generateCode(): string
    {
        do {
            $code = 'TEM' . strtoupper(substr(uniqid(), -7));
        } while (Temoignage::where('code_tem', $code)->exists());
        return $code;
    }

    public function index()
    {
        $user = Auth::user();
        if ($user && $user->role_user === 'alumni') {
            $temoignages = Temoignage::where('code_user', $user->code_user)->paginate(10);
        } else {
            $temoignages = Temoignage::paginate(12);
        }
        return view('temoignages.index', compact('temoignages'));
    }

    public function create()
    {
        return view('temoignages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contenu_tem' => 'required|string|min:20',
            'code_promo'  => 'nullable|exists:promotions,code_promo',
        ]);

        $user = Auth::user();

        Temoignage::create([
            'code_tem'    => $this->generateCode(),
            'contenu_tem' => $validated['contenu_tem'],
            'valide_tem'  => false,
            'code_user'   => $user->code_user,
            'code_promo'  => $validated['code_promo'] ?? $user->code_promo,
        ]);

        $redirectRoute = $user->role_user === 'admin'
            ? route('admin.temoignages.index')
            : route('alumni.temoignages.index');

        return redirect($redirectRoute)->with('success', 'Témoignage soumis ! Il sera visible après validation.');
    }

    public function show($code_tem)
    {
        $temoignage = Temoignage::with(['utilisateur', 'promotion'])->findOrFail($code_tem);
        $commentaires = Commentaire::where('code_tem', $code_tem)->with('utilisateur')->get();
        return view('temoignages.show', compact('temoignage', 'commentaires'));
    }

    public function edit($code_tem)
    {
        $temoignage = Temoignage::findOrFail($code_tem);
        $this->authorizeAccess($temoignage);
        return view('temoignages.edit', compact('temoignage'));
    }

    public function update(Request $request, $code_tem)
    {
        $temoignage = Temoignage::findOrFail($code_tem);
        $this->authorizeAccess($temoignage);

        $validated = $request->validate([
            'contenu_tem' => 'required|string|min:20',
            'valide_tem'  => 'sometimes|boolean',
        ]);

        // Si un alumni modifie son témoignage, remettre en attente de validation
        $user = Auth::user();
        if ($user->role_user !== 'admin') {
            $validated['valide_tem'] = false;
        }

        $temoignage->update($validated);

        $redirectRoute = $user->role_user === 'admin'
            ? route('admin.temoignages.index')
            : route('alumni.temoignages.index');

        return redirect($redirectRoute)->with('success', 'Témoignage mis à jour !');
    }

    public function destroy($code_tem)
    {
        $temoignage = Temoignage::findOrFail($code_tem);
        $this->authorizeAccess($temoignage);
        $temoignage->delete();

        $user = Auth::user();
        $redirectRoute = $user->role_user === 'admin'
            ? route('admin.temoignages.index')
            : route('alumni.temoignages.index');

        return redirect($redirectRoute)->with('success', 'Témoignage supprimé !');
    }

    private function authorizeAccess(Temoignage $temoignage)
    {
        $user = Auth::user();
        if ($user->role_user !== 'admin' && $temoignage->code_user !== $user->code_user) {
            abort(403);
        }
    }
}
