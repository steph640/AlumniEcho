@extends('template')

@section('main')
<div class="container">
    <h1>Éditer commentaire : {{ $commentaire->code_com }}</h1>

    @if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>@endif

    <form action="{{ url('/web/commentaires/'.$commentaire->code_com) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Contenu</label>
            <textarea name="contenu_com" class="form-control" rows="4">{{ old('contenu_com', $commentaire->contenu_com) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Code souvenir</label>
            <input type="text" name="code_souv" class="form-control" value="{{ old('code_souv', $commentaire->code_souv) }}">
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ url('/web/commentaires') }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection
