@extends('template')
@section('title', 'Partager un Témoignage - AlumniEcho')

@section('styles')
<style>
    .tip-box {
        background:#e8f4fd; border-left:4px solid var(--primary,#4C3B7F);
        padding:12px 16px; border-radius:0 8px 8px 0;
    }
</style>
@endsection

@section('main')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="page-header-green">
            <h4 class="fw-bold mb-0"><i class="bi bi-chat-quote me-2"></i>Partager un Témoignage</h4>
            <p class="mb-0 opacity-75 small">Votre témoignage sera visible après validation par un administrateur</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="tip-box mb-4">
            <i class="bi bi-lightbulb me-2 text-primary"></i>
            <strong>Conseils :</strong> Partagez votre parcours après l'école, vos conseils aux futurs diplômés,
            vos réussites professionnelles ou personnelles.
        </div>

        <div class="card p-4">
            <form action="{{ route('alumni.temoignages.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-semibold">Votre témoignage <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="contenu_tem" rows="10"
                              placeholder="Partagez votre expérience, vos conseils et votre parcours après l'école..."
                              required minlength="20">{{ old('contenu_tem') }}</textarea>
                    <div class="form-text">Minimum 20 caractères</div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-send me-1"></i>Soumettre le témoignage
                    </button>
                    <a href="{{ route('alumni.dashboard') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
