@extends('template')
@section('title', 'Témoignages - AlumniEcho')

@section('styles')
<style>
    .tem-card { border-radius:14px; transition:transform .25s,box-shadow .25s; }
    .tem-card:hover { transform:translateY(-4px); box-shadow:0 10px 28px rgba(45,80,22,.18) !important; }
    .quote-mark { font-size:4rem; color:#e0d8f5; line-height:.8; float:left; margin-right:8px; font-family:Georgia,serif; }
    .badge-valide   { background:linear-gradient(135deg,#2D5016,#4A7C2D); }
    .badge-attente  { background:linear-gradient(135deg,#D97706,#F59E0B); color:#1a1a1a; }
</style>
@endsection

@section('main')

<div class="page-header-green d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0"><i class="bi bi-chat-quote me-2"></i>Témoignages</h4>
        <p class="mb-0 opacity-75 small">{{ $temoignages->total() }} témoignage(s)</p>
    </div>
    @auth
    @if(auth()->user()->role_user === 'alumni')
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalCreer">
        <i class="bi bi-plus-lg me-1"></i>Nouveau témoignage
    </button>
    @endif
    @endauth
</div>

@if($temoignages->count())
<div class="row g-4">
    @foreach($temoignages as $t)
    <div class="col-md-6">
        <div class="card tem-card h-100 p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                @auth
                @if(auth()->user()->role_user === 'admin')
                <span class="badge {{ $t->valide_tem ? 'badge-valide' : 'badge-attente' }} px-2 py-1">
                    {{ $t->valide_tem ? '✓ Validé' : '⏳ En attente' }}
                </span>
                @endif
                @endauth
                <small class="text-muted ms-auto">{{ $t->created_at->diffForHumans() }}</small>
            </div>
            <div class="flex-grow-1 mb-3">
                <span class="quote-mark">"</span>
                <p class="mb-0" style="line-height:1.7">{{ Str::limit($t->contenu_tem, 200) }}</p>
            </div>
            <div class="d-flex justify-content-between align-items-end border-top pt-3">
                <div>
                    @if($t->utilisateur)
                    <div class="fw-semibold small text-primary">{{ $t->utilisateur->prenom_user }} {{ $t->utilisateur->nom_user }}</div>
                    @endif
                    @if($t->promotion)
                    <small class="text-muted">{{ $t->promotion->nom_promo }}</small>
                    @endif
                </div>
                @auth
                @php $role = auth()->user()->role_user; @endphp
                <div class="d-flex gap-1">
                    @if($role === 'admin' || $t->code_user === auth()->user()->code_user)
                    <button class="btn btn-warning btn-sm"
                        data-bs-toggle="modal" data-bs-target="#modalEditer"
                        data-code="{{ $t->code_tem }}"
                        data-contenu="{{ $t->contenu_tem }}"
                        data-valide="{{ $t->valide_tem ? '1' : '0' }}"
                        title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </button>
                    @if($role === 'admin')
                    <form method="POST" action="{{ route('admin.temoignages.update', $t->code_tem) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="contenu_tem" value="{{ $t->contenu_tem }}">
                        <input type="hidden" name="valide_tem" value="{{ $t->valide_tem ? 0 : 1 }}">
                        <button type="submit" class="btn btn-sm {{ $t->valide_tem ? 'btn-outline-secondary' : 'btn-success' }}"
                                title="{{ $t->valide_tem ? 'Désactiver' : 'Valider' }}">
                            <i class="bi {{ $t->valide_tem ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route($role.'.temoignages.destroy', $t->code_tem) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Supprimer ce témoignage ?')" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
                @endauth
            </div>
        </div>
    </div>
    @endforeach
</div>
{{ $temoignages->links('pagination::bootstrap-5') }}

@else
<div class="text-center py-5">
    <i class="bi bi-chat-quote text-muted" style="font-size:4rem;opacity:.3"></i>
    <h5 class="text-muted mt-3">Aucun témoignage pour le moment</h5>
</div>
@endif

{{-- ══ MODAL CRÉER ══ --}}
@auth
@if(auth()->user()->role_user === 'alumni')
<div class="modal fade" id="modalCreer" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-chat-quote me-2"></i>Partager un Témoignage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('alumni.temoignages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bi bi-info-circle me-1"></i>Votre témoignage sera visible après validation par un administrateur.
                    </div>
                    @if($errors->any())
                    <div class="alert alert-danger py-2 small">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                    @endif
                    <label class="form-label fw-semibold">Votre témoignage <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="contenu_tem" rows="7"
                              placeholder="Partagez votre parcours, vos conseils, votre expérience après l'école..."
                              required minlength="20">{{ old('contenu_tem') }}</textarea>
                    <div class="form-text">Minimum 20 caractères</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endauth

{{-- ══ MODAL ÉDITER (pattern data-*) ══ --}}
@auth
@if(in_array(auth()->user()->role_user, ['admin','alumni']))
@php $role = auth()->user()->role_user; @endphp
<div class="modal fade" id="modalEditer" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier le Témoignage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditerTem" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <label class="form-label fw-semibold">Contenu <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="contenu_tem" id="edit_contenu" rows="7" required minlength="20"></textarea>
                    @if($role === 'admin')
                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" name="valide_tem" id="edit_valide" value="1">
                        <label class="form-check-label fw-semibold" for="edit_valide">Témoignage validé (visible publiquement)</label>
                    </div>
                    @endif
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
const modalEditerTem = document.getElementById('modalEditer');
if (modalEditerTem) {
    modalEditerTem.addEventListener('show.bs.modal', function(event) {
        const btn    = event.relatedTarget;
        const code   = btn.getAttribute('data-code');
        const contenu = btn.getAttribute('data-contenu');
        const valide = btn.getAttribute('data-valide');

        document.getElementById('formEditerTem').action = '/{{ auth()->user()->role_user ?? "alumni" }}/temoignages/' + code;
        document.getElementById('edit_contenu').value = contenu;

        const cb = document.getElementById('edit_valide');
        if (cb) cb.checked = valide === '1';
    });
}

@if($errors->any() && old('contenu_tem'))
    new bootstrap.Modal(document.getElementById('modalCreer')).show();
@endif
</script>
@endsection
