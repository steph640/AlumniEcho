@extends('template')
@section('title', 'Espace Visiteur - AlumniEcho')

@section('main')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Espace Visiteur</h3>
        <p class="text-muted mb-0">Bienvenue, {{ auth()->user()->prenom_user }} 👋</p>
    </div>
    <span class="badge fs-6 px-3 py-2 bg-secondary">Visiteur</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="card border-0 shadow-sm p-3 text-center" style="border-radius:12px;">
            <div class="fs-2 fw-bold text-primary">{{ $stats['souvenirs'] }}</div>
            <div class="text-muted small">Souvenirs disponibles</div>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 shadow-sm p-3 text-center" style="border-radius:12px;">
            <div class="fs-2 fw-bold" style="color:#2D5016">{{ $stats['temoignages'] }}</div>
            <div class="text-muted small">Témoignages</div>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 shadow-sm p-3 text-center" style="border-radius:12px;">
            <div class="fs-2 fw-bold text-warning">{{ $stats['alumnis'] }}</div>
            <div class="text-muted small">Alumni</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
            <div class="p-3" style="background:linear-gradient(135deg,#4C3B7F,#6B5BA8);color:white;">
                <h6 class="fw-bold mb-0"><i class="bi bi-images me-2"></i>Derniers Souvenirs</h6>
            </div>
            <div class="p-3">
                @forelse($recentSouvenirs as $s)
                <a href="{{ route('visiteur.souvenirs.show', $s->code_souv) }}" class="d-flex align-items-center gap-2 mb-2 text-decoration-none text-dark">
                    <div style="width:40px;height:40px;background:#f0ecfb;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-image text-primary"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold small text-truncate">{{ $s->titre_souv }}</div>
                        <small class="text-muted">{{ $s->created_at->diffForHumans() }}</small>
                    </div>
                </a>
                @empty
                <p class="text-muted small">Aucun souvenir.</p>
                @endforelse
                <a href="{{ route('visiteur.souvenirs.index') }}" class="btn btn-outline-primary btn-sm w-100 mt-2">Voir tout</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
            <div class="p-3" style="background:linear-gradient(135deg,#2D5016,#4A7C2D);color:white;">
                <h6 class="fw-bold mb-0"><i class="bi bi-chat-quote me-2"></i>Témoignages Récents</h6>
            </div>
            <div class="p-3">
                @forelse($recentTemoignages as $t)
                <div class="mb-2 pb-2 border-bottom">
                    <p class="small mb-1">"{{ Str::limit($t->contenu_tem, 80) }}"</p>
                    <small class="text-muted">{{ optional($t->utilisateur)->prenom_user }}</small>
                </div>
                @empty
                <p class="text-muted small">Aucun témoignage.</p>
                @endforelse
                <a href="{{ route('visiteur.temoignages.index') }}" class="btn btn-sm w-100 mt-2 text-white" style="background:#2D5016;">Voir tout</a>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 p-4 rounded-3 text-center" style="background:linear-gradient(135deg,#f0ecfb,#e8f5e9);">
    <h6 class="fw-bold text-primary">Vous voulez contribuer ?</h6>
    <p class="text-muted small mb-3">Créez un compte alumni pour partager vos souvenirs et témoignages.</p>
    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-4">
        <i class="bi bi-person-plus me-1"></i>Devenir alumni
    </a>
</div>
@endsection
