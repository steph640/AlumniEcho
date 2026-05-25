@extends('template')

@section('main')
<div class="container">
    <h1>Éditer filière : {{ $filiere->code_fil }}</h1>

    @if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>@endif

    <form action="{{ url('/web/filieres/'.$filiere->code_fil) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom_fil" class="form-control" value="{{ old('nom_fil', $filiere->nom_fil) }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description_fil" class="form-control">{{ old('description_fil', $filiere->description_fil) }}</textarea>
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ url('/web/filieres') }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection
