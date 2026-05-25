@extends('template')

@section('main')
    <div class="container">
        <h1>Ajouter une promotion</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>@endif

        <form action="{{ url('/web/promotions') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Code</label>
                <input type="text" name="code_promo" class="form-control" value="{{ old('code_promo') }}" required>
            </div>

            <div class="mb-3">
                <label>Nom</label>
                <input type="text" name="nom_promo" class="form-control" value="{{ old('nom_promo') }}" required>
            </div>

            <div class="mb-3">
                <label>Année</label>
                <input type="number" name="annee_promo" class="form-control" value="{{ old('annee_promo') }}" required>
            </div>

            <div class="mb-3">
                <label>Code filière</label>
                <input type="text" name="code_fil" class="form-control" value="{{ old('code_fil') }}">
            </div>

            <button class="btn btn-success">Enregistrer</button>
            <a href="{{ url('/web/promotions') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
