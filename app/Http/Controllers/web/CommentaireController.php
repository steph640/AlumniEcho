<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use App\Models\Souvenir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    private function generateCode(): string
    {
        do {
            $code = 'COM' . strtoupper(substr(uniqid(), -7));
        } while (Commentaire::where('code_com', $code)->exists());
        return $code;
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->role_user === 'alumni') {
            $commentaires = Commentaire::where('code_user', $user->code_user)->paginate(10);
        } else {
            $commentaires = Commentaire::paginate(15);
        }
        return view('commentaires.index', compact('commentaires'));
    }

    public function create()
    {
        $souvenirs = Souvenir::all();
        return view('commentaires.create', compact('souvenirs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contenu_com' => 'required|string|min:5',
            'code_souv'   => 'required|exists:souvenirs,code_souv',
        ]);

        $user = Auth::user();

        Commentaire::create([
            'code_com'    => $this->generateCode(),
            'contenu_com' => $validated['contenu_com'],
            'code_user'   => $user->code_user,
            'code_souv'   => $validated['code_souv'],
        ]);

        return back()->with('success', 'Commentaire ajouté !');
    }

    public function edit($code_com)
    {
        $commentaire = Commentaire::findOrFail($code_com);
        $souvenirs = Souvenir::all();
        $this->authorizeAccess($commentaire);
        return view('commentaires.edit', compact('commentaire', 'souvenirs'));
    }

    public function update(Request $request, $code_com)
    {
        $commentaire = Commentaire::findOrFail($code_com);
        $this->authorizeAccess($commentaire);

        $validated = $request->validate([
            'contenu_com' => 'required|string|min:5',
        ]);

        $commentaire->update($validated);

        $user = Auth::user();
        $route = $user->role_user === 'admin' ? route('admin.commentaires.index') : route('alumni.commentaires.index');
        return redirect($route)->with('success', 'Commentaire modifié !');
    }

    public function destroy($code_com)
    {
        $commentaire = Commentaire::findOrFail($code_com);
        $this->authorizeAccess($commentaire);
        $commentaire->delete();

        $user = Auth::user();
        $route = $user->role_user === 'admin' ? route('admin.commentaires.index') : route('alumni.commentaires.index');
        return redirect($route)->with('success', 'Commentaire supprimé !');
    }

    private function authorizeAccess(Commentaire $commentaire)
    {
        $user = Auth::user();
        if ($user->role_user !== 'admin' && $commentaire->code_user !== $user->code_user) {
            abort(403);
        }
    }
}
