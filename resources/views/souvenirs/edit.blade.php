@extends('template')
@section('title', 'Modifier le Souvenir - AlumniEcho')

@section('main')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="page-header">
            <h4 class="fw-bold mb-0"><i class="bi bi-pencil me-2"></i>Modifier le souvenir</h4>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="card p-4">
            <form action="{{ request()->is('admin/*') ? route('admin.souvenirs.update', $souvenir->code_souv) : route('alumni.souvenirs.update', $souvenir->code_souv) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="titre_souv"
                           value="{{ old('titre_souv', $souvenir->titre_souv) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="description_souv" rows="6" required>{{ old('description_souv', $souvenir->description_souv) }}</textarea>
                </div>

                @if($souvenir->url_photo_souv)
                <div class="mb-3">
                    <label class="form-label fw-semibold">Photo actuelle</label>
                    <div>
                        <img src="{{ $souvenir->url_photo_souv }}" class="rounded"
                             style="max-height:140px;object-fit:cover;" alt="Photo actuelle">
                    </div>
                </div>
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nouvelle photo <span class="text-muted fw-normal small">(optionnel)</span></label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Promotion</label>
                        <select class="form-select" name="code_promo">
                            <option value="">— Aucune —</option>
                            @foreach($promotions as $promo)
                            <option value="{{ $promo->code_promo }}" {{ $souvenir->code_promo == $promo->code_promo ? 'selected' : '' }}>
                                {{ $promo->nom_promo }} ({{ $promo->annee_promo }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>
                    <a href="{{ request()->is('admin/*') ? route('admin.souvenirs.index') : route('alumni.souvenirs.index') }}"
                       class="btn btn-outline-secondary px-4">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
