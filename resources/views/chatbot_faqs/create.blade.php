@extends('template')

@section('main')
    <div class="container">
        <h1>Ajouter une FAQ</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>@endif

        <form action="{{ url('/web/chatbot_faqs') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Code</label>
                <input type="text" name="code_faq" class="form-control" value="{{ old('code_faq') }}" required>
            </div>

            <div class="mb-3">
                <label>Question</label>
                <input type="text" name="question_faq" class="form-control" value="{{ old('question_faq') }}" required>
            </div>

            <div class="mb-3">
                <label>Réponse</label>
                <textarea name="reponse_faq" class="form-control" rows="5" required>{{ old('reponse_faq') }}</textarea>
            </div>

            <button class="btn btn-success">Enregistrer</button>
            <a href="{{ url('/web/chatbot_faqs') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
