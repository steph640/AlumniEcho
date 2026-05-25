@extends('template')

@section('main')
<div class="container">
    <h1>Ajouter une filière</h1>

    @if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>@endif

    <form action="{{ url('/web/filieres') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Code</label>
            <input type="text" name="code_fil" class="form-control" value="{{ old('code_fil') }}" required>
        </div>

        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom_fil" class="form-control" value="{{ old('nom_fil') }}" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description_fil" class="form-control">{{ old('description_fil') }}</textarea>
        </div>

        <button class="btn btn-success">Enregistrer</button>
        <a href="{{ url('/web/filieres') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
