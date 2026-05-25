@extends('template')
@section('title', 'Dashboard Alumni - AlumniEcho')

@section('styles')
<style>
    .stat-card { border:none; border-radius:12px; box-shadow:0 2px 12px rgba(76,59,127,.1); transition:transform .2s; }
    .stat-card:hover { transform:translateY(-3px); }
    .stat-icon { width:52px; height:52px; border-radius:12px; display:flex;align-items:center;justify-content:center;font-size:1.5rem; }
    .souvenir-mini { border:none; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,.07); overflow:hidden; }
    .souvenir-mini .img-top { height:100px; object-fit:cover; }
    .placeholder-img { height:100px; background:linear-gradient(135deg,#e8e4f5,#f0ecfb); display:flex;align-items:center;justify-content:center;font-size:2rem;color:#6B5BA8; }
</style>
@endsection

@section('main')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Mon Espace Alumni</h3>
        <p class="text-muted mb-0">Bienvenue, {{ auth()->user()->prenom_user }} 👋</p>
    </div>
    <span class="badge fs-6 px-3 py-2" style="background:#2D5016">Alumni</span>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['Mes Souvenirs', $stats['souvenirs'], 'bi-images', '#4C3B7F', '#f0ecfb'],
        ['Mes Témoignages', $stats['temoignages'], 'bi-chat-quote-fill', '#2D5016', '#e8f5e9'],
        ['Mes Commentaires', $stats['commentaires'], 'bi-chat-dots-fill', '#1E40AF', '#dbeafe'],
    ] as [$label, $val, $icon, $color, $bg])
    <div class="col-4">
        <div class="card stat-card p-3 text-center">
            <div class="stat-icon mx-auto mb-2" style="background:{{ $bg }};color:{{ $color }}">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div class="fs-2 fw-bold" style="color:{{ $color }}">{{ $val }}</div>
            <div class="text-muted small">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Actions rapides --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4" style="border-radius:12px;">
            <h6 class="fw-bold mb-3"><i class="bi bi-images me-2 text-primary"></i>Mes Souvenirs</h6>
            <p class="text-muted small mb-3">Partagez vos meilleurs moments de promotion.</p>
            <div class="d-flex gap-2">
                <a href="{{ route('alumni.souvenirs.index') }}" class="btn btn-outline-primary btn-sm">Voir tout</a>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalSouvenir">
                    <i class="bi bi-plus-lg me-1"></i>Nouveau
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4" style="border-radius:12px;">
            <h6 class="fw-bold mb-3"><i class="bi bi-chat-quote me-2" style="color:#2D5016"></i>Mes Témoignages</h6>
            <p class="text-muted small mb-3">Partagez votre parcours avec les futures promotions.</p>
            <div class="d-flex gap-2">
                <a href="{{ route('alumni.temoignages.index') }}" class="btn btn-sm" style="border:1px solid #2D5016;color:#2D5016;">Voir tout</a>
                <button class="btn btn-sm text-white" style="background:#2D5016;" data-bs-toggle="modal" data-bs-target="#modalTemoignage">
                    <i class="bi bi-plus-lg me-1"></i>Nouveau
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Derniers souvenirs --}}
@if($recentSouvenirs->count())
<h5 class="fw-bold mb-3">Mes derniers souvenirs</h5>
<div class="row g-3 mb-4">
    @foreach($recentSouvenirs as $souv)
    <div class="col-4">
        <div class="card souvenir-mini h-100">
            @if($souv->url_photo_souv)
            <img src="{{ $souv->url_photo_souv }}" class="img-top w-100">
            @else
            <div class="placeholder-img"><i class="bi bi-image"></i></div>
            @endif
            <div class="p-2">
                <div class="fw-semibold small text-truncate">{{ $souv->titre_souv }}</div>
                <small class="text-muted">{{ $souv->created_at->diffForHumans() }}</small>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- MODAL SOUVENIR RAPIDE --}}
<div class="modal fade" id="modalSouvenir" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-images me-2"></i>Nouveau Souvenir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('alumni.souvenirs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="titre_souv" required placeholder="Ex: Remise des diplômes 2024">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description_souv" rows="4" required placeholder="Décrivez ce moment..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Photo (optionnel)</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Publier</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TÉMOIGNAGE RAPIDE --}}
<div class="modal fade" id="modalTemoignage" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-chat-quote me-2"></i>Nouveau Témoignage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('alumni.temoignages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2 small"><i class="bi bi-info-circle me-1"></i>Visible après validation admin.</div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Votre témoignage <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="contenu_tem" rows="6" required minlength="20"
                                  placeholder="Partagez votre parcours et vos conseils..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
