<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AlumniEcho')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary:       #4C3B7F;
            --primary-light: #6B5BA8;
            --primary-pale:  #f0ecfb;
            --gold:          #D4AF37;
            --gold-dark:     #B8960E;
            --green:         #2D5016;
            --green-light:   #4A7C2D;
            --danger-soft:   #FEE2E2;
        }

        /* ── Base ── */
        body { display:flex; flex-direction:column; min-height:100vh; background:#f5f3f8; font-family:'Segoe UI',system-ui,sans-serif; }
        main { flex:1; }

        /* ── Navbar ── */
        .navbar { background:linear-gradient(135deg,var(--primary) 0%,var(--primary-light) 100%) !important; box-shadow:0 4px 14px rgba(76,59,127,.25); }
        .navbar-brand { font-weight:800; font-size:1.35rem; letter-spacing:-.3px; }
        .nav-link { transition:color .2s; font-weight:500; }
        .nav-link:hover,.nav-link.active { color:var(--gold) !important; }
        .dropdown-menu { border:none; box-shadow:0 8px 24px rgba(76,59,127,.15); border-radius:10px; }
        .dropdown-item:hover { background:var(--primary-pale); color:var(--primary); }

        /* ── Boutons ── */
        .btn-primary   { background:linear-gradient(135deg,var(--primary),var(--primary-light)); border:none; font-weight:600; }
        .btn-primary:hover { background:linear-gradient(135deg,#3a2d63,var(--primary)); border:none; }
        .btn-secondary { background:#6c757d; border:none; font-weight:600; }
        .btn-success   { background:linear-gradient(135deg,var(--green),var(--green-light)); border:none; font-weight:600; }
        .btn-success:hover { background:linear-gradient(135deg,#1f3a0e,var(--green)); border:none; }
        .btn-warning   { background:linear-gradient(135deg,var(--gold),#C9992C); border:none; font-weight:600; color:#1a1a1a !important; }
        .btn-warning:hover { background:linear-gradient(135deg,var(--gold-dark),var(--gold)); border:none; color:#1a1a1a !important; }
        .btn-danger    { background:linear-gradient(135deg,#DC2626,#EF4444); border:none; font-weight:600; }
        .btn-danger:hover { background:linear-gradient(135deg,#b91c1c,#DC2626); border:none; }
        .btn-outline-primary  { border:1.5px solid var(--primary); color:var(--primary); font-weight:600; }
        .btn-outline-primary:hover  { background:var(--primary); color:white; }
        .btn-outline-success  { border:1.5px solid var(--green); color:var(--green); font-weight:600; }
        .btn-outline-success:hover  { background:var(--green); color:white; }
        .btn-outline-warning  { border:1.5px solid var(--gold); color:var(--gold-dark); font-weight:600; }
        .btn-outline-warning:hover  { background:var(--gold); color:#1a1a1a; }
        .btn-outline-danger   { border:1.5px solid #DC2626; color:#DC2626; font-weight:600; }
        .btn-outline-danger:hover   { background:#DC2626; color:white; }
        .btn-outline-secondary { border:1.5px solid #6c757d; color:#6c757d; font-weight:600; }
        .btn-outline-secondary:hover { background:#6c757d; color:white; }

        /* ── Cards ── */
        .card { border:none !important; border-radius:12px; box-shadow:0 2px 12px rgba(76,59,127,.09); }
        .card:hover { box-shadow:0 6px 20px rgba(76,59,127,.16); transition:box-shadow .2s; }

        /* ── Tables ── */
        .table thead th { background:linear-gradient(135deg,var(--primary),var(--primary-light)); color:white; font-weight:600; border:none; white-space:nowrap; }
        .table tbody tr:hover { background:var(--primary-pale); }
        .table-responsive { border-radius:12px; overflow:hidden; box-shadow:0 2px 12px rgba(76,59,127,.09); }

        /* ── Badges ── */
        .badge-role-admin   { background:var(--primary); }
        .badge-role-alumni  { background:var(--green); }
        .badge-role-visiteur{ background:#6c757d; }
        .badge-gold { background:var(--gold); color:#1a1a1a; }

        /* ── Modal ── */
        .modal-header { background:linear-gradient(135deg,var(--primary),var(--primary-light)); color:white; border-radius:10px 10px 0 0; }
        .modal-header .btn-close { filter:brightness(0) invert(1); }
        .modal-content { border:none; border-radius:12px; box-shadow:0 16px 40px rgba(76,59,127,.25); }

        /* ── Page headers ── */
        .page-header { background:linear-gradient(135deg,var(--primary),var(--primary-light)); color:white; border-radius:12px; padding:22px 28px; margin-bottom:24px; }
        .page-header-green { background:linear-gradient(135deg,var(--green),var(--green-light)); color:white; border-radius:12px; padding:22px 28px; margin-bottom:24px; }

        /* ── Flash toast ── */
        .flash-toast { position:fixed; top:80px; right:20px; z-index:9999; min-width:300px; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.15); animation:slideIn .3s ease; }
        @keyframes slideIn { from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:translateX(0)} }

        /* ── Pagination ── */
        .pagination .page-link { border-radius:8px !important; margin:0 2px; transition:all .2s; }
        .pagination .page-item.active .page-link { background:linear-gradient(135deg,var(--primary),var(--primary-light)); border:none; }

        /* ── Misc ── */
        footer { background:linear-gradient(135deg,var(--primary),var(--primary-light)); color:white; padding:1.5rem 0; margin-top:auto; }
        .form-control:focus,.form-select:focus { border-color:var(--primary-light); box-shadow:0 0 0 .25rem rgba(107,91,168,.2); }
    </style>
    @yield('styles')
</head>
<body>

{{-- ══ NAVBAR ══ --}}
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/"><i class="bi bi-mortarboard-fill me-2"></i>AlumniEcho</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                        <i class="bi bi-house-door me-1"></i>Accueil
                    </a>
                </li>

                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-images me-1"></i>Souvenirs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-chat-quote me-1"></i>Témoignages</a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-outline-light btn-sm px-3" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
                    </a>
                </li>
                <li class="nav-item ms-lg-1">
                    <a class="btn btn-warning btn-sm px-3" href="{{ route('register') }}">
                        <i class="bi bi-person-plus me-1"></i>S'inscrire
                    </a>
                </li>
                @endguest

                @auth
                @php $role = auth()->user()->role_user; @endphp

                @if($role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-grid me-1"></i>Gestion
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.utilisateurs.index') }}"><i class="bi bi-people me-2 text-primary"></i>Utilisateurs</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.souvenirs.index') }}"><i class="bi bi-images me-2 text-primary"></i>Souvenirs</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.temoignages.index') }}"><i class="bi bi-chat-quote me-2 text-primary"></i>Témoignages</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.commentaires.index') }}"><i class="bi bi-chat-dots me-2 text-primary"></i>Commentaires</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.filieres.index') }}"><i class="bi bi-diagram-3 me-2 text-primary"></i>Filières</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.promotions.index') }}"><i class="bi bi-calendar-event me-2 text-primary"></i>Promotions</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.chatbot') }}"><i class="bi bi-robot me-2 text-primary"></i>Chatbot FAQ</a></li>
                    </ul>
                </li>

                @elseif($role === 'alumni')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('alumni/dashboard') ? 'active' : '' }}" href="{{ route('alumni.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('alumni/souvenirs*') ? 'active' : '' }}" href="{{ route('alumni.souvenirs.index') }}">
                        <i class="bi bi-images me-1"></i>Mes Souvenirs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('alumni/temoignages*') ? 'active' : '' }}" href="{{ route('alumni.temoignages.index') }}">
                        <i class="bi bi-chat-quote me-1"></i>Témoignages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('alumni.chatbot') }}">
                        <i class="bi bi-robot me-1"></i>Chatbot
                    </a>
                </li>

                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('visiteur.souvenirs.index') }}"><i class="bi bi-images me-1"></i>Souvenirs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('visiteur.temoignages.index') }}"><i class="bi bi-chat-quote me-1"></i>Témoignages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('visiteur.chatbot') }}"><i class="bi bi-robot me-1"></i>Chatbot</a>
                </li>
                @endif

                {{-- Avatar utilisateur --}}
                <li class="nav-item dropdown ms-lg-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                        <span class="badge badge-role-{{ $role }} rounded-pill px-2 py-1">{{ ucfirst($role) }}</span>
                        <span class="d-none d-lg-inline fw-semibold">{{ auth()->user()->prenom_user }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2">
                            <div class="fw-bold text-primary">{{ auth()->user()->prenom_user }} {{ auth()->user()->nom_user }}</div>
                            <small class="text-muted">{{ auth()->user()->login_user }}</small>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        @if($role === 'alumni')
                        <li><a class="dropdown-item" href="{{ route('alumni.profile') }}"><i class="bi bi-person-circle me-2"></i>Mon profil</a></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-semibold">
                                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>

{{-- ══ FLASH MESSAGES AUTO-DISMISS 5s ══ --}}
@if(session('success'))
<div id="flashMsg" class="flash-toast alert alert-success alert-dismissible d-flex align-items-center" role="alert">
    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
    <div class="flex-grow-1 fw-semibold">{{ session('success') }}</div>
    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
</div>
@elseif(session('error'))
<div id="flashMsg" class="flash-toast alert alert-danger alert-dismissible d-flex align-items-center" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
    <div class="flex-grow-1 fw-semibold">{{ session('error') }}</div>
    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ══ CONTENU ══ --}}
<main class="container my-4">
    @yield('main')
</main>

{{-- ══ FOOTER ══ --}}
<footer>
    <div class="container text-center">
        <p class="mb-1 fw-semibold"><i class="bi bi-mortarboard-fill me-2"></i>AlumniEcho</p>
        <p class="mb-0 opacity-60 small">Plateforme de mémoires et souvenirs de promotions &nbsp;·&nbsp; &copy; {{ date('Y') }}</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ── Auto-dismiss flash après 5s ──
(function() {
    const flash = document.getElementById('flashMsg');
    if (!flash) return;
    setTimeout(() => {
        flash.style.transition = 'opacity .5s ease, transform .5s ease';
        flash.style.opacity = '0';
        flash.style.transform = 'translateX(40px)';
        setTimeout(() => flash.remove(), 500);
    }, 5000);
})();
</script>
@yield('scripts')
</body>
</html>
