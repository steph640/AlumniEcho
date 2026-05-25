@extends('template')
@section('title', 'Inscription - AlumniEcho')

@section('styles')
<style>
    .auth-card { max-width: 540px; margin: 30px auto; }
    .auth-header { background: linear-gradient(135deg, #2D5016, #4A7C2D); color: white; padding: 24px; border-radius: 12px 12px 0 0; text-align: center; }
</style>
@endsection

@section('main')
<div class="auth-card">
    <div class="auth-header">
        <i class="bi bi-person-plus-fill fs-1 mb-2 d-block"></i>
        <h4 class="fw-bold mb-1">Créer un compte Alumni</h4>
        <p class="opacity-75 mb-0 small">Rejoignez la communauté AlumniEcho</p>
    </div>
    <div class="card border-0 shadow" style="border-radius:0 0 12px 12px;">
        <div class="card-body p-4">
            @if($errors->any())
            <div class="alert alert-danger py-2">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            @endif
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nom_user" value="{{ old('nom_user') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="prenom_user" value="{{ old('prenom_user') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Identifiant <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="login_user" value="{{ old('login_user') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Téléphone</label>
                        <input type="text" class="form-control" name="tel_user" value="{{ old('tel_user') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_user" required minlength="6">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Confirmer <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_user_confirmation" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Sexe</label>
                        <select class="form-select" name="sexe_user">
                            <option value="">--</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Promotion</label>
                        <select class="form-select" name="code_promo">
                            <option value="">-- Choisir --</option>
                            @foreach(\App\Models\Promotion::all() as $p)
                            <option value="{{ $p->code_promo }}">{{ $p->nom_promo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold mt-4">
                    <i class="bi bi-person-plus me-2"></i>Créer mon compte
                </button>
            </form>
            <hr class="my-3">
            <p class="text-center text-muted mb-0 small">
                Déjà inscrit ? <a href="{{ route('login') }}" class="fw-semibold">Se connecter</a>
            </p>
        </div>
    </div>
</div>
@endsection
