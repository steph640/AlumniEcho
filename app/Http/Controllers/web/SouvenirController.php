<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Souvenir;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SouvenirController extends Controller
{
    /**
     * Génère un code souvenir unique
     */
    private function generateCode(): string
    {
        do {
            $code = 'SV' . strtoupper(substr(uniqid(), -8));
        } while (Souvenir::where('code_souv', $code)->exists());
        return $code;
    }

    public function index()
    {
        $user = Auth::user();
        if ($user && $user->role_user === 'alumni') {
            $souvenirs = Souvenir::where('code_user', $user->code_user)->paginate(10);
        } else {
            $souvenirs = Souvenir::paginate(12);
        }
        $promotions = Promotion::all();
        return view('souvenirs.index', compact('souvenirs', 'promotions'));
    }

    public function create()
    {
        $promotions = Promotion::all();
        return view('souvenirs.create', compact('promotions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre_souv'       => 'required|string|max:255',
            'description_souv' => 'required|string',
            'photo'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'code_promo'       => 'nullable|exists:promotions,code_promo',
        ]);

        $user = Auth::user();
        $url_photo = null;

        // Gestion upload photo
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $path = $request->file('photo')->store('souvenirs', 'public');
            $url_photo = '/files/' . $path;

            // Copier le fichier vers public/storage aussi
            $sourceFile = storage_path('app/public/' . $path);
            $destDir = public_path('storage/souvenirs');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            copy($sourceFile, $destDir . '/' . basename($path));
        }

        Souvenir::create([
            'code_souv'        => $this->generateCode(),
            'titre_souv'       => $validated['titre_souv'],
            'description_souv' => $validated['description_souv'],
            'url_photo_souv'   => $url_photo,
            'code_user'        => $user->code_user,
            'code_promo'       => $validated['code_promo'] ?? $user->code_promo,
        ]);

        $redirectRoute = $user->role_user === 'admin'
            ? route('admin.souvenirs.index')
            : route('alumni.souvenirs.index');

        return redirect($redirectRoute)->with('success', 'Souvenir créé avec succès !');
    }

    public function show($code_souv)
    {
        $souvenir = Souvenir::with(['utilisateur', 'promotion'])->findOrFail($code_souv);
        return view('souvenirs.show', compact('souvenir'));
    }

    public function edit($code_souv)
    {
        $souvenir = Souvenir::findOrFail($code_souv);
        $promotions = Promotion::all();
        $this->authorizeAccess($souvenir);
        return view('souvenirs.edit', compact('souvenir', 'promotions'));
    }

    public function update(Request $request, $code_souv)
    {
        $souvenir = Souvenir::findOrFail($code_souv);
        $this->authorizeAccess($souvenir);

        $validated = $request->validate([
            'titre_souv'       => 'required|string|max:255',
            'description_souv' => 'required|string',
            'photo'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'code_promo'       => 'nullable|exists:promotions,code_promo',
        ]);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Supprimer l'ancienne photo si elle existe
            if ($souvenir->url_photo_souv) {
                $oldPath = str_replace('/files/', '', $souvenir->url_photo_souv);
                Storage::disk('public')->delete($oldPath);
                // Aussi supprimer du public/storage
                $oldFile = public_path('storage/' . $oldPath);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            $path = $request->file('photo')->store('souvenirs', 'public');
            $validated['url_photo_souv'] = '/files/' . $path;

            // Copier le fichier vers public/storage aussi
            $sourceFile = storage_path('app/public/' . $path);
            $destDir = public_path('storage/souvenirs');
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            copy($sourceFile, $destDir . '/' . basename($path));
        }

        unset($validated['photo']);
        $souvenir->update($validated);

        $user = Auth::user();
        $redirectRoute = $user->role_user === 'admin'
            ? route('admin.souvenirs.index')
            : route('alumni.souvenirs.index');

        return redirect($redirectRoute)->with('success', 'Souvenir mis à jour !');
    }

    public function destroy($code_souv)
    {
        $souvenir = Souvenir::findOrFail($code_souv);
        $this->authorizeAccess($souvenir);

        if ($souvenir->url_photo_souv) {
            $oldPath = str_replace('/files/', '', $souvenir->url_photo_souv);
            Storage::disk('public')->delete($oldPath);
            // Aussi supprimer du public/storage
            $oldFile = public_path('storage/' . $oldPath);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $souvenir->delete();

        $user = Auth::user();
        $redirectRoute = $user->role_user === 'admin'
            ? route('admin.souvenirs.index')
            : route('alumni.souvenirs.index');

        return redirect($redirectRoute)->with('success', 'Souvenir supprimé !');
    }

    private function authorizeAccess(Souvenir $souvenir)
    {
        $user = Auth::user();
        if ($user->role_user !== 'admin' && $souvenir->code_user !== $user->code_user) {
            abort(403, 'Vous ne pouvez pas modifier ce souvenir.');
        }
    }
}
