@extends('template')
@section('title', 'Mon Profil - AlumniEcho')

@section('main')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="page-header">
            <h4 class="fw-bold mb-0"><i class="bi bi-person-circle me-2"></i>Mon Profil</h4>
            <p class="mb-0 opacity-75 small">Gérez vos informations personnelles</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2 small mb-3">
            @foreach($errors->all() as $error)
            <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success py-2 small mb-3">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        </div>
        @endif

        <div class="card p-4">
            <form action="{{ route('alumni.profile.update') }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nom_user"
                               value="{{ $user->nom_user }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="prenom_user"
                               value="{{ $user->prenom_user }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Téléphone</label>
                        <input type="tel" class="form-control" name="tel_user"
                               value="{{ $user->tel_user }}" placeholder="Ex: 699 000 000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Sexe</label>
                        <select class="form-select" name="sexe_user">
                            <option value="">— Sélectionner —</option>
                            <option value="M" {{ $user->sexe_user == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ $user->sexe_user == 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Filière</label>
                        <select class="form-select" name="code_fil">
                            <option value="">— Sélectionner —</option>
                            @foreach($filieres as $filiere)
                            <option value="{{ $filiere->code_fil }}"
                                {{ $user->code_fil == $filiere->code_fil ? 'selected' : '' }}>
                                {{ $filiere->nom_fil ?? $filiere->code_fil }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Promotion</label>
                        <select class="form-select" name="code_promo">
                            <option value="">— Sélectionner —</option>
                            @foreach($promotions as $promo)
                            <option value="{{ $promo->code_promo }}"
                                {{ $user->code_promo == $promo->code_promo ? 'selected' : '' }}>
                                {{ $promo->nom_promo ?? $promo->code_promo }}
                                @if($promo->annee_promo) ({{ $promo->annee_promo }}) @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <hr class="my-1">
                        <label class="form-label fw-semibold">
                            Nouveau mot de passe
                            <span class="text-muted fw-normal small">(laisser vide pour ne pas changer)</span>
                        </label>
                        <input type="password" class="form-control" name="password_user"
                               minlength="6" placeholder="••••••">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" name="password_user_confirmation"
                               minlength="6" placeholder="••••••">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                    </button>
                    <a href="{{ route('alumni.dashboard') }}" class="btn btn-outline-secondary px-4">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
