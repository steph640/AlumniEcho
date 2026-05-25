@extends('template')
@section('title', $souvenir->titre_souv . ' - AlumniEcho')

@section('styles')
<style>
    .hero-souv {
        background: linear-gradient(135deg, var(--primary, #4C3B7F), var(--primary-light, #6B5BA8));
        color: white; padding: 32px; border-radius: 14px; margin-bottom: 24px;
    }
    .photo-souv { width:100%; max-height:420px; object-fit:cover; border-radius:12px; }
</style>
@endsection

@section('main')
<div class="hero-souv">
    <h2 class="fw-bold mb-2">{{ $souvenir->titre_souv }}</h2>
    <div class="d-flex flex-wrap gap-3" style="opacity:.85">
        @if($souvenir->utilisateur)
        <span><i class="bi bi-person me-1"></i>{{ $souvenir->utilisateur->prenom_user }} {{ $souvenir->utilisateur->nom_user }}</span>
        @endif
        @if($souvenir->promotion)
        <span><i class="bi bi-mortarboard me-1"></i>{{ $souvenir->promotion->nom_promo }}</span>
        @endif
        <span><i class="bi bi-calendar me-1"></i>{{ $souvenir->created_at->format('d/m/Y') }}</span>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        @if($souvenir->url_photo_souv)
        <img src="{{ $souvenir->url_photo_souv }}" class="photo-souv mb-4" alt="{{ $souvenir->titre_souv }}">
        @endif
        <div class="card p-4">
            <p class="lead mb-0" style="white-space:pre-line;line-height:1.8">{{ $souvenir->description_souv }}</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h6 class="fw-bold text-muted text-uppercase small mb-3">Informations</h6>

            @if($souvenir->utilisateur)
            <div class="mb-3">
                <div class="fw-semibold small"><i class="bi bi-person-circle me-1 text-primary"></i>Auteur</div>
                <div class="text-muted">{{ $souvenir->utilisateur->prenom_user }} {{ $souvenir->utilisateur->nom_user }}</div>
            </div>
            @endif

            @if($souvenir->promotion)
            <div class="mb-3">
                <div class="fw-semibold small"><i class="bi bi-mortarboard me-1 text-primary"></i>Promotion</div>
                <div class="text-muted">{{ $souvenir->promotion->nom_promo }} ({{ $souvenir->promotion->annee_promo }})</div>
            </div>
            @endif

            <div class="mb-3">
                <div class="fw-semibold small"><i class="bi bi-clock me-1 text-primary"></i>Publié le</div>
                <div class="text-muted">{{ $souvenir->created_at->format('d/m/Y à H:i') }}</div>
            </div>

            @auth
            @if(auth()->user()->role_user === 'admin' || $souvenir->code_user === auth()->user()->code_user)
            @php $role = auth()->user()->role_user; @endphp
            <hr>
            <div class="d-grid gap-2">
                <a href="{{ $role === 'admin' ? route('admin.souvenirs.edit', $souvenir->code_souv) : route('alumni.souvenirs.edit', $souvenir->code_souv) }}"
                   class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i>Modifier</a>
                <form method="POST" action="{{ $role === 'admin' ? route('admin.souvenirs.destroy', $souvenir->code_souv) : route('alumni.souvenirs.destroy', $souvenir->code_souv) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100"
                            onclick="return confirm('Supprimer définitivement ce souvenir ?')">
                        <i class="bi bi-trash me-1"></i>Supprimer
                    </button>
                </form>
            </div>
            @endif
            @endauth
        </div>
    </div>
</div>
@endsection
