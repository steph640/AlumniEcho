@extends('template')
@section('title', 'Témoignage - AlumniEcho')

@section('styles')
<style>
    .hero-tem {
        background: linear-gradient(135deg, var(--green,#2D5016), var(--green-light,#4A7C2D));
        color: white; padding: 32px; border-radius: 14px; margin-bottom: 24px;
    }
    blockquote {
        border-left: 4px solid var(--primary,#4C3B7F);
        padding-left: 20px; font-size: 1.1rem;
        line-height: 1.8; color: #333;
    }
</style>
@endsection

@section('main')
<div class="hero-tem">
    <div class="mb-3">
        <span class="badge {{ $temoignage->valide_tem ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">
            {{ $temoignage->valide_tem ? '✓ Témoignage validé' : '⏳ En attente de validation' }}
        </span>
    </div>
    <h2 class="fw-bold mb-2">Témoignage d'alumni</h2>
    <div class="d-flex flex-wrap gap-3" style="opacity:.85">
        @if($temoignage->utilisateur)
        <span><i class="bi bi-person me-1"></i>{{ $temoignage->utilisateur->prenom_user }} {{ $temoignage->utilisateur->nom_user }}</span>
        @endif
        @if($temoignage->promotion)
        <span><i class="bi bi-mortarboard me-1"></i>{{ $temoignage->promotion->nom_promo }}</span>
        @endif
        <span><i class="bi bi-calendar me-1"></i>{{ $temoignage->created_at->format('d/m/Y') }}</span>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card p-4 mb-4">
            <blockquote>{{ $temoignage->contenu_tem }}</blockquote>
        </div>

        <div class="card p-4">
            <h5 class="fw-bold mb-4">
                <i class="bi bi-chat-dots me-2"></i>Commentaires ({{ $commentaires->count() }})
            </h5>
            @forelse($commentaires as $com)
            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <div class="fw-semibold small">
                        {{ $com->utilisateur->prenom_user ?? 'Anonyme' }}
                        {{ $com->utilisateur->nom_user ?? '' }}
                    </div>
                    <p class="mb-1 text-muted">{{ $com->contenu_com }}</p>
                    <small class="text-muted">{{ $com->created_at->diffForHumans() }}</small>
                </div>
            </div>
            @empty
            <p class="text-muted text-center py-3">
                Aucun commentaire pour l'instant. Soyez le premier !
            </p>
            @endforelse
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h6 class="fw-bold text-muted text-uppercase small mb-3">Auteur</h6>
            @if($temoignage->utilisateur)
            <div class="mb-2">
                <div class="fw-semibold">
                    {{ $temoignage->utilisateur->prenom_user }} {{ $temoignage->utilisateur->nom_user }}
                </div>
                @if($temoignage->promotion)
                <div class="text-muted small">{{ $temoignage->promotion->nom_promo }}</div>
                @endif
            </div>
            @endif

            @auth
            @if(auth()->user()->role_user === 'admin' || $temoignage->code_user === auth()->user()->code_user)
            @php $role = auth()->user()->role_user; @endphp
            <hr>
            <div class="d-grid gap-2">
                <a href="{{ $role === 'admin' ? route('admin.temoignages.edit', $temoignage->code_tem) : route('alumni.temoignages.edit', $temoignage->code_tem) }}"
                   class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i>Modifier</a>
                <form method="POST" action="{{ $role === 'admin' ? route('admin.temoignages.destroy', $temoignage->code_tem) : route('alumni.temoignages.destroy', $temoignage->code_tem) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100"
                            onclick="return confirm('Supprimer ce témoignage ?')">
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
