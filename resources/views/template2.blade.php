<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AlumniEcho</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root{--nav:#003366;--muted:#e0e0e0;--text:#0b2540;}
        body{display:flex;flex-direction:column;min-height:100vh;color:var(--text);margin:0;}
        main{flex:1;padding:1.5rem;}
        .navbar{background:var(--nav)!important;}
        .navbar-brand{color:#fff;font-weight:700;}
        .nav-link{color:rgba(255,255,255,0.92);padding:.35rem .6rem;}
        .nav-link.active{background:var(--muted);color:var(--nav)!important;border-radius:6px;}
        .btn-primary{background:var(--nav);color:#fff;border-radius:6px;border:none;padding:.45rem .9rem;}
        footer{background:var(--nav);color:#fff;padding:.75rem 0;text-align:center;}
        .card{border-radius:6px;}
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/"><i class="bi bi-mortarboard-fill me-2"></i>AlumniEcho</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/"><i class="bi bi-house-door-fill me-1"></i>Accueil</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('web/utilisateurs*') ? 'active' : '' }}" href="/web/utilisateurs">Utilisateurs</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('web/souvenirs*') ? 'active' : '' }}" href="/web/souvenirs">Souvenirs</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('web/temoignages*') ? 'active' : '' }}" href="/web/temoignages">Témoignages</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('web/promotions*') ? 'active' : '' }}" href="/web/promotions">Promotions</a></li>
            </ul>

            <ul class="navbar-nav align-items-center">
                <li class="nav-item me-2 d-none d-lg-block">
                    <button class="btn btn-outline-light btn-sm" data-bs-toggle="offcanvas" data-bs-target="#chatbotSidebar"><i class="bi bi-chat-dots me-1"></i>Chat</button>
                </li>

                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i><span class="d-none d-lg-inline">{{ auth()->user()->nom_user ?? auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="/profile">Profil</a></li>
                        <li><a class="dropdown-item" href="/settings">Paramètres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item"><a class="nav-link btn btn-outline-light btn-sm text-white" href="/login">Connexion</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@if(request()->is('/'))
<header class="text-center" style="padding:40px 0;border-bottom:1px solid rgba(0,0,0,0.04);background:linear-gradient(180deg,#fff 0%,#f7f9fb 100%);">
    <div class="container">
        <h1 class="display-5 fw-bold">Bienvenue sur AlumniEcho 🎓</h1>
        <p class="lead text-muted">La plateforme qui connecte les anciens étudiants, leurs souvenirs et leurs témoignages.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-3">
            <a href="/web/promotions" class="btn btn-primary btn-lg"><i class="bi bi-calendar-event me-2"></i>Promotions</a>
            <a href="/web/filieres" class="btn btn-outline-secondary btn-lg"><i class="bi bi-diagram-3 me-2"></i>Filières</a>
        </div>
    </div>
</header>
@endif

<main class="container">
    @yield('main')
</main>

<div class="offcanvas offcanvas-end" tabindex="-1" id="chatbotSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title"><i class="bi bi-robot me-2"></i>Chatbot</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    <div id="chatbot-messages" style="max-height:420px;overflow:auto;" class="mb-3">
      <div class="alert alert-secondary">👋 Bonjour !</div>
    </div>
    <form onsubmit="return false;" class="mt-auto">
      <div class="input-group">
        <input id="chat-input" class="form-control" placeholder="Votre message...">
        <button id="chat-send" class="btn btn-primary" type="button"><i class="bi bi-send"></i></button>
      </div>
    </form>
  </div>
</div>

<footer>
    <div class="container">
        <small>&copy; 2026 AlumniEcho — Tous droits réservés</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('chat-send')?.addEventListener('click', function(){
    const input = document.getElementById('chat-input');
    const v = input.value.trim(); if(!v) return;
    const c = document.getElementById('chatbot-messages');
    const u = document.createElement('div'); u.className='text-end mb-2'; u.innerHTML='<div class="d-inline-block bg-primary text-white p-2 rounded">'+v+'</div>';
    c.appendChild(u); input.value=''; c.scrollTop=c.scrollHeight;
    setTimeout(()=>{ const b=document.createElement('div'); b.className='text-start mb-2'; b.innerHTML='<div class="d-inline-block bg-light text-dark p-2 rounded">Réponse automatique.</div>'; c.appendChild(b); c.scrollTop=c.scrollHeight; },700);
});
</script>
</body>
</html>
