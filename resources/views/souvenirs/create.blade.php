@extends('template')
@section('title', 'Créer un Souvenir - AlumniEcho')

@section('main')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="page-header">
            <h4 class="fw-bold mb-0"><i class="bi bi-images me-2"></i>Créer un Souvenir</h4>
            <p class="mb-0 opacity-75 small">Partagez un moment mémorable de votre promotion</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="card p-4">
            <form action="{{ request()->is('admin/*') ? route('admin.souvenirs.store') : route('alumni.souvenirs.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Titre du souvenir <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="titre_souv"
                           value="{{ old('titre_souv') }}"
                           placeholder="Ex: Cérémonie de remise des diplômes 2024" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="description_souv" rows="6"
                              placeholder="Décrivez ce moment... Qui était là, ce qui s'est passé, les émotions ressenties..."
                              required>{{ old('description_souv') }}</textarea>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Photo <span class="text-muted fw-normal small">(optionnel)</span></label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                        <div class="form-text">JPG, PNG, WebP — max 4 Mo</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Promotion associée</label>
                        <select class="form-select" name="code_promo">
                            <option value="">— Ma promotion (par défaut) —</option>
                            @foreach($promotions as $promo)
                            <option value="{{ $promo->code_promo }}" {{ old('code_promo') == $promo->code_promo ? 'selected' : '' }}>
                                {{ $promo->nom_promo }} ({{ $promo->annee_promo }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Publier le souvenir
                    </button>
                    <a href="{{ request()->is('admin/*') ? route('admin.souvenirs.index') : route('alumni.souvenirs.index') }}"
                       class="btn btn-outline-secondary px-4">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
