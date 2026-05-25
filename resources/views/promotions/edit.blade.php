@extends('template')

@section('main')
    <div class="container">
        <h1>Éditer promotion : {{ $promotion->code_promo }}</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>@endif

        <form action="{{ url('/web/promotions/' . $promotion->code_promo) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Nom</label>
                <input type="text" name="nom_promo" class="form-control"
                    value="{{ old('nom_promo', $promotion->nom_promo) }}">
            </div>

            <div class="mb-3">
                <label>Année</label>
                <input type="number" name="annee_promo" class="form-control"
                    value="{{ old('annee_promo', $promotion->annee_promo) }}">
            </div>

            <div class="mb-3">
                <label>Code filière</label>
                <input type="text" name="code_fil" class="form-control" value="{{ old('code_fil', $promotion->code_fil) }}">
            </div>

            <button class="btn btn-primary">Mettre à jour</button>
            <a href="{{ url('/web/promotions') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection
