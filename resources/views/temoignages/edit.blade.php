@extends('template')
@section('title', 'Modifier le Témoignage - AlumniEcho')

@section('main')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="page-header">
            <h4 class="fw-bold mb-0"><i class="bi bi-pencil me-2"></i>Modifier le témoignage</h4>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="card p-4">
            <form action="{{ auth()->user()->role_user === 'admin' ? route('admin.temoignages.update', $temoignage->code_tem) : route('alumni.temoignages.update', $temoignage->code_tem) }}"
                  method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Contenu <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="contenu_tem" rows="10"
                              required minlength="20">{{ old('contenu_tem', $temoignage->contenu_tem) }}</textarea>
                </div>

                @if(auth()->user()->role_user === 'admin')
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="valide_tem"
                           id="valide_tem" value="1" {{ $temoignage->valide_tem ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="valide_tem">
                        Témoignage validé (visible publiquement)
                    </label>
                </div>
                @endif

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>
                    <a href="{{ auth()->user()->role_user === 'admin' ? route('admin.temoignages.index') : route('alumni.temoignages.index') }}"
                       class="btn btn-outline-secondary px-4">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
