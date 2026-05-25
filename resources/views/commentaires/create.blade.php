@extends('template')

@section('main')
    <div class="container">
        <h1>Ajouter un commentaire</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>@endif

        <form action="{{ url('/web/commentaires') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Code</label>
                <input type="text" name="code_com" class="form-control" value="{{ old('code_com') }}" required>
            </div>

            <div class="mb-3">
                <label>Contenu</label>
                <textarea name="contenu_com" class="form-control" rows="4" required>{{ old('contenu_com') }}</textarea>
            </div>

            <div class="mb-3">
                <label>Code souvenir</label>
                <input type="text" name="code_souv" class="form-control" value="{{ old('code_souv') }}">
            </div>

            <div class="mb-3">
                <label>Code utilisateur</label>
                <input type="text" name="code_user" class="form-control" value="{{ old('code_user') }}">
            </div>

            <button class="btn btn-success">Enregistrer</button>
            <a href="{{ url('/web/commentaires') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
