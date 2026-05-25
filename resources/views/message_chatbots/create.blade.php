@extends('template')
@section('title', 'Nouveau message chatbot - AlumniEcho')

@section('main')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="page-header">
            <h4 class="fw-bold mb-0"><i class="bi bi-robot me-2"></i>Nouveau message chatbot</h4>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="card p-4">
            <form action="{{ url('/web/message_chatbots') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Code</label>
                    <input type="text" name="code_message" class="form-control"
                           value="{{ old('code_message') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Contenu</label>
                    <textarea name="contenu_msg" class="form-control" rows="4"
                              required>{{ old('contenu_msg') }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Code utilisateur</label>
                    <input type="text" name="code_user" class="form-control"
                           value="{{ old('code_user') }}">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>
                    <a href="{{ url('/web/message_chatbots') }}" class="btn btn-outline-secondary px-4">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
