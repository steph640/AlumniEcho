@extends('template')

@section('main')
    <div class="container">
        <h1>Éditer message : {{ $message->code_message }}</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>@endif

        <form action="{{ url('/web/message_chatbots/' . $message->code_message) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Contenu</label>
                <textarea name="contenu_msg" class="form-control"
                    rows="4">{{ old('contenu_msg', $message->contenu_msg) }}</textarea>
            </div>

            <div class="mb-3">
                <label>Code utilisateur</label>
                <input type="text" name="code_user" class="form-control"
                    value="{{ old('code_user', $message->code_user) }}">
            </div>

            <button class="btn btn-primary">Mettre à jour</button>
            <a href="{{ url('/web/message_chatbots') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection
