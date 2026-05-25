<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AlumniEcho — Futuriste</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root{--bg:#0d1b2a;--accent:#00b4d8;--card:#0f2430;--text:#e6f7fb;}
        body{background:#07121a;color:var(--text);display:flex;flex-direction:column;min-height:100vh;}
        main{flex:1;padding:1.5rem;}
        .navbar{background:var(--bg)!important;border-bottom:1px solid rgba(255,255,255,0.03);}
        .navbar .navbar-brand{color:var(--accent);font-weight:700;}
        .nav-link{color:rgba(255,255,255,0.85);}
        .nav-link.active{background:var(--accent);color:var(--bg)!important;border-radius:6px;padding:.35rem .6rem;}
        .btn-primary{background:var(--accent);color:var(--bg);border-radius:4px;padding:.5rem 1rem;box-shadow:0 0 6px rgba(0,180,216,0.25);}
        .btn-primary:hover{box-shadow:0 0 18px rgba(0,180,216,0.45);}
        .hero-section{background:linear-gradient(90deg, rgba(0,180,216,0.06), rgba(255,255,255,0));padding:48px 0;border-bottom:1px solid rgba(255,255,255,0.02);}
        footer{background:var(--bg);color:var(--accent);padding:1rem 0;border-top:1px solid rgba(255,255,255,0.02);}
        .card{background:var(--card);border:none;border-radius:8px;color:var(--text);}
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/"><i class="bi bi-cpu-fill me-2"></i>AlumniEcho</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav3">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav3">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Accueil</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->is('web/competences*') ? 'active' : '' }}" href="/web/competences">Compétences</a></li>
                <li class="nav-item"><a class="nav-link" href="/login"><i class="bi bi-person-badge"></i> Connexion</a></li>
            </ul>
        </div>
    </div>
</nav>

@if(request()->is('/'))
<header class="hero-section text-center">
    <div class="container">
        <h1 class="display-5 fw-bold">AlumniEcho — Tech & Réseau</h1>
        <p class="lead text-muted">Connectez compétences et opportunités dans un espace moderne.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
            <a href="/web/competences" class="btn btn-primary btn-lg"><i class="bi bi-award me-2"></i>Voir compétences</a>
            <a href="/web/utilisateurs" class="btn btn-outline-light btn-lg"><i class="bi bi-people me-2"></i>Utilisateurs</a>
        </div>
    </div>
</header>
@endif

<main class="container">
    @yield('main')
</main>

<footer class="text-center">
    <div class="container">
        <small>&copy; 2026 AlumniEcho — Tech & Réseau.</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
