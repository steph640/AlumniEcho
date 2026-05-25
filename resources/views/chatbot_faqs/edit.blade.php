@extends('template')

@section('main')
    <div class="container">
        <h1>Éditer FAQ : {{ $faq->code_faq }}</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>@endif

        <form action="{{ url('/web/chatbot_faqs/' . $faq->code_faq) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Question</label>
                <input type="text" name="question_faq" class="form-control"
                    value="{{ old('question_faq', $faq->question_faq) }}">
            </div>

            <div class="mb-3">
                <label>Réponse</label>
                <textarea name="reponse_faq" class="form-control"
                    rows="5">{{ old('reponse_faq', $faq->reponse_faq) }}</textarea>
            </div>

            <button class="btn btn-primary">Mettre à jour</button>
            <a href="{{ url('/web/chatbot_faqs') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection
