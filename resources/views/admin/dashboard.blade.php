@extends('template')
@section('title', 'Dashboard Admin - AlumniEcho')

@section('styles')
<style>
    .stat-card { border:none; border-radius:12px; box-shadow:0 2px 12px rgba(76,59,127,.1); transition:transform .2s; }
    .stat-card:hover { transform:translateY(-3px); }
    .stat-icon { width:56px; height:56px; border-radius:12px; display:flex;align-items:center;justify-content:center;font-size:1.6rem; }
    .quick-link { border:none; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,.07); transition:all .2s; text-decoration:none; }
    .quick-link:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(76,59,127,.15); }
</style>
@endsection

@section('main')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Tableau de Bord Administrateur</h3>
        <p class="text-muted mb-0">Bienvenue, {{ auth()->user()->prenom_user }} 👋</p>
    </div>
    <span class="badge fs-6 px-3 py-2" style="background:#4C3B7F">Administrateur</span>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['Utilisateurs', $stats['users'], 'bi-people-fill', '#4C3B7F', '#f0ecfb'],
        ['Alumni', $stats['alumnis'], 'bi-mortarboard-fill', '#2D5016', '#e8f5e9'],
        ['Visiteurs', $stats['visiteurs'], 'bi-eye-fill', '#1E40AF', '#dbeafe'],
        ['Souvenirs', $stats['souvenirs'], 'bi-images', '#B45309', '#fef3c7'],
        ['Témoignages', $stats['temoignages'], 'bi-chat-quote-fill', '#6D28D9', '#ede9fe'],
        ['Commentaires', $stats['commentaires'], 'bi-chat-dots-fill', '#0F766E', '#ccfbf1'],
    ] as [$label, $val, $icon, $color, $bg])
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card p-3 h-100">
            <div class="stat-icon mb-2" style="background:{{ $bg }};color:{{ $color }}">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div class="fs-3 fw-bold" style="color:{{ $color }}">{{ $val }}</div>
            <div class="text-muted small">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Accès rapides --}}
<h5 class="fw-bold mb-3">Accès rapides</h5>
<div class="row g-3 mb-4">
    @foreach([
        ['Gérer les utilisateurs', route('admin.utilisateurs.index'), 'bi-people', '#4C3B7F'],
        ['Gérer les souvenirs', route('admin.souvenirs.index'), 'bi-images', '#B45309'],
        ['Valider les témoignages', route('admin.temoignages.index'), 'bi-chat-quote', '#2D5016'],
        ['Gérer les promotions', route('admin.promotions.index'), 'bi-calendar-event', '#1E40AF'],
        ['Gérer les filières', route('admin.filieres.index'), 'bi-diagram-3', '#6D28D9'],
        ['Chatbot FAQ', route('admin.chatbot'), 'bi-robot', '#0F766E'],
    ] as [$label, $url, $icon, $color])
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ $url }}" class="quick-link d-flex flex-column align-items-center p-3 text-center bg-white h-100">
            <i class="bi {{ $icon }} fs-3 mb-2" style="color:{{ $color }}"></i>
            <span class="small fw-semibold text-dark">{{ $label }}</span>
        </a>
    </div>
    @endforeach
</div>

{{-- Derniers utilisateurs --}}
<h5 class="fw-bold mb-3">Derniers utilisateurs inscrits</h5>
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nom</th><th>Login</th><th>Rôle</th><th>État</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $u)
                <tr>
                    <td>{{ $u->prenom_user }} {{ $u->nom_user }}</td>
                    <td class="text-muted">{{ $u->login_user }}</td>
                    <td><span class="badge badge-role-{{ $u->role_user }}">{{ ucfirst($u->role_user) }}</span></td>
                    <td><span class="badge {{ $u->etat_user === 'actif' ? 'bg-success' : 'bg-secondary' }}">{{ $u->etat_user }}</span></td>
                    <td><a href="{{ route('admin.utilisateurs.edit', $u->code_user) }}" class="btn btn-sm btn-outline-primary">Éditer</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
