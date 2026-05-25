@extends('template')
@section('title', 'Souvenirs - AlumniEcho')

@section('styles')
<style>
    .card-souv { border-radius:14px; overflow:hidden; transition:transform .25s,box-shadow .25s; }
    .card-souv:hover { transform:translateY(-5px); box-shadow:0 10px 28px rgba(76,59,127,.2) !important; }
    .card-souv .thumb { height:175px; object-fit:cover; width:100%; }
    .thumb-placeholder { height:175px; background:linear-gradient(135deg,#e8e4f5,#f0ecfb); display:flex; align-items:center; justify-content:center; font-size:2.8rem; color:var(--primary-light,#6B5BA8); }
</style>
@endsection

@section('main')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-images me-2"></i>Souvenirs</h4>
        <p class="mb-0 opacity-75 small">{{ $souvenirs->total() }} souvenir(s)</p>
    </div>
    @auth
    @if(auth()->user()->role_user !== 'visiteur')
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalCreer">
        <i class="bi bi-plus-lg me-1"></i>Nouveau souvenir
    </button>
    @endif
    @endauth
</div>

@if($souvenirs->count())
<div class="row g-4">
    @foreach($souvenirs as $s)
    <div class="col-md-6 col-lg-4">
        <div class="card card-souv h-100">
            @if($s->url_photo_souv)
                <img src="{{ $s->url_photo_souv }}" class="thumb" alt="{{ $s->titre_souv }}">
            @else
                <div class="thumb-placeholder"><i class="bi bi-image"></i></div>
            @endif
            <div class="card-body d-flex flex-column">
                <h6 class="fw-bold mb-1">{{ $s->titre_souv }}</h6>
                <p class="text-muted small flex-grow-1 mb-2">{{ Str::limit($s->description_souv, 90) }}</p>
                @if($s->promotion)
                <span class="badge badge-gold mb-2 d-inline-block" style="font-size:.75rem;">
                    <i class="bi bi-mortarboard me-1"></i>{{ $s->promotion->nom_promo }}
                </span>
                @endif
                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $s->created_at->diffForHumans() }}</small>
            </div>
            <div class="card-footer bg-white border-0 d-flex gap-2 pb-3 px-3">
                @auth
                @php $role = auth()->user()->role_user; @endphp
                <a href="{{ route($role.'.souvenirs.show', $s->code_souv) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                    <i class="bi bi-eye me-1"></i>Voir
                </a>
                @if($role === 'admin' || $s->code_user === auth()->user()->code_user)
                <button class="btn btn-warning btn-sm"
                    data-bs-toggle="modal" data-bs-target="#modalEditer"
                    data-code="{{ $s->code_souv }}"
                    data-titre="{{ $s->titre_souv }}"
                    data-description="{{ $s->description_souv }}"
                    data-promo="{{ $s->code_promo }}"
                    title="Modifier">
                    <i class="bi bi-pencil"></i>
                </button>
                <form method="POST" action="{{ route($role.'.souvenirs.destroy', $s->code_souv) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Supprimer ce souvenir définitivement ?')" title="Supprimer">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                @endif
                @endauth
            </div>
        </div>
    </div>
    @endforeach
</div>
{{ $souvenirs->links() }}

@else
<div class="text-center py-5">
    <i class="bi bi-images text-muted" style="font-size:4rem;opacity:.3"></i>
    <h5 class="text-muted mt-3">Aucun souvenir pour le moment</h5>
    @auth
    @if(auth()->user()->role_user !== 'visiteur')
    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalCreer">
        <i class="bi bi-plus-lg me-1"></i>Publier le premier souvenir
    </button>
    @endif
    @endauth
</div>
@endif

{{-- ══ MODAL CRÉER ══ --}}
@auth
@if(auth()->user()->role_user !== 'visiteur')
@php $role = auth()->user()->role_user; @endphp
<div class="modal fade" id="modalCreer" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-images me-2"></i>Nouveau Souvenir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route($role.'.souvenirs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                    <div class="alert alert-danger py-2 small">
                        @foreach($errors->all() as $e)<div><i class="bi bi-exclamation-circle me-1"></i>{{ $e }}</div>@endforeach
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="titre_souv" value="{{ old('titre_souv') }}"
                               placeholder="Ex: Cérémonie de remise des diplômes 2024" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description_souv" rows="4"
                                  placeholder="Décrivez ce moment mémorable..." required>{{ old('description_souv') }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Photo <span class="text-muted fw-normal small">(optionnel)</span></label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                            <div class="form-text">JPG, PNG, WebP — max 4 Mo</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Promotion</label>
                            <select class="form-select" name="code_promo">
                                <option value="">— Ma promotion —</option>
                                @foreach($promotions as $p)
                                <option value="{{ $p->code_promo }}">{{ $p->nom_promo }} ({{ $p->annee_promo }})</option>
                                @endforeach
                            </select>
                        </div>
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

{{-- ══ MODAL ÉDITER (pattern data-*) ══ --}}
<div class="modal fade" id="modalEditer" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier le Souvenir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditer" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="titre_souv" id="edit_titre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description_souv" id="edit_desc" rows="4" required></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nouvelle photo <span class="text-muted fw-normal small">(optionnel)</span></label>
                            <input type="file" class="form-control" name="photo" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Promotion</label>
                            <select class="form-select" name="code_promo" id="edit_promo">
                                <option value="">— Aucune —</option>
                                @foreach($promotions as $p)
                                <option value="{{ $p->code_promo }}">{{ $p->nom_promo }} ({{ $p->annee_promo }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endauth
@endsection

@section('scripts')
<script>
// Pattern data-* pour le modal d'édition
const modalEditer = document.getElementById('modalEditer');
if (modalEditer) {
    modalEditer.addEventListener('show.bs.modal', function(event) {
        const btn = event.relatedTarget;
        const code  = btn.getAttribute('data-code');
        const titre = btn.getAttribute('data-titre');
        const desc  = btn.getAttribute('data-description');
        const promo = btn.getAttribute('data-promo');

        // Mise à jour URL du formulaire
        document.getElementById('formEditer').action = '/{{ auth()->user()->role_user ?? "alumni" }}/souvenirs/' + code;

        // Remplissage des champs
        document.getElementById('edit_titre').value = titre;
        document.getElementById('edit_desc').value  = desc;
        const sel = document.getElementById('edit_promo');
        if (sel) sel.value = promo || '';
    });
}

// Ré-ouvrir modal si erreur de validation
@if($errors->any() && old('titre_souv'))
    new bootstrap.Modal(document.getElementById('modalCreer')).show();
@endif
</script>
@endsection
