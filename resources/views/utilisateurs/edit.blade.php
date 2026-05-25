@extends('template')

@section('main')
    <div class="container">
        <h1>Éditer utilisateur : {{ $utilisateur->code_user }}</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ url('/web/utilisateurs/'.$utilisateur->code_user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" name="nom_user" class="form-control" value="{{ old('nom_user', $utilisateur->nom_user) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom_user" class="form-control" value="{{ old('prenom_user', $utilisateur->prenom_user) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" name="password_user" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" name="tel_user" class="form-control" value="{{ old('tel_user', $utilisateur->tel_user) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Rôle</label>
                <select name="role_user" class="form-control">
                    <option value="visiteur" {{ old('role_user', $utilisateur->role_user)=='visiteur' ? 'selected' : '' }}>visiteur</option>
                    <option value="alumni" {{ old('role_user', $utilisateur->role_user)=='alumni' ? 'selected' : '' }}>alumni</option>
                    <option value="admin" {{ old('role_user', $utilisateur->role_user)=='admin' ? 'selected' : '' }}>admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">État</label>
                <select name="etat_user" class="form-control">
                    <option value="actif" {{ old('etat_user', $utilisateur->etat_user)=='actif' ? 'selected' : '' }}>actif</option>
                    <option value="inactif" {{ old('etat_user', $utilisateur->etat_user)=='inactif' ? 'selected' : '' }}>inactif</option>
                </select>
            </div>

            <button class="btn btn-primary">Mettre à jour</button>
            <a href="{{ url('/web/utilisateurs') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection
