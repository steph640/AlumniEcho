@extends('template')

@section('main')
<div class="container">
    <h1>Créer un utilisateur</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ url('/web/utilisateurs') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Code</label>
            <input type="text" name="code_user" class="form-control" value="{{ old('code_user') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom_user" class="form-control" value="{{ old('nom_user') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom_user" class="form-control" value="{{ old('prenom_user') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Login</label>
            <input type="text" name="login_user" class="form-control" value="{{ old('login_user') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password_user" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <input type="text" name="tel_user" class="form-control" value="{{ old('tel_user') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Sexe</label>
            <select name="sexe_user" class="form-control">
                <option value="">--</option>
                <option value="M" {{ old('sexe_user')=='M' ? 'selected' : '' }}>M</option>
                <option value="F" {{ old('sexe_user')=='F' ? 'selected' : '' }}>F</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Rôle</label>
            <select name="role_user" class="form-control" required>
                <option value="visiteur" {{ old('role_user')=='visiteur' ? 'selected' : '' }}>visiteur</option>
                <option value="alumni" {{ old('role_user')=='alumni' ? 'selected' : '' }}>alumni</option>
                <option value="admin" {{ old('role_user')=='admin' ? 'selected' : '' }}>admin</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">État</label>
            <select name="etat_user" class="form-control" required>
                <option value="actif" {{ old('etat_user')=='actif' ? 'selected' : '' }}>actif</option>
                <option value="inactif" {{ old('etat_user')=='inactif' ? 'selected' : '' }}>inactif</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Code promo</label>
            <input type="text" name="code_promo" class="form-control" value="{{ old('code_promo') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Code filière</label>
            <input type="text" name="code_fil" class="form-control" value="{{ old('code_fil') }}">
        </div>

        <button class="btn btn-success">Enregistrer</button>
        <a href="{{ url('/web/utilisateurs') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
