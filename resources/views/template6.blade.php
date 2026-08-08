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
            body{display:flex;flex-direction:column;min-height:100vh;color:var(--text);}
            main{flex:1;padding:1.5rem;}
            .navbar{background:var(--nav)!important;}
            .navbar .navbar-brand{color:#fff;font-weight:700;}
            .nav-link{color:rgba(255,255,255,0.92);}
            .nav-link:hover{color:#fff;opacity:0.95;}
            .nav-link.active{background:var(--muted);color:var(--nav)!important;border-radius:6px;padding:.35rem .6rem;}
            .btn-primary{background:var(--nav);border-radius:6px;color:#fff;padding:.5rem 1rem;border:none;}
            .btn-primary:hover{background:var(--muted);color:var(--nav);border:1px solid var(--nav);}
            .badge-notif { position: absolute; top: 6px; right: 6px; font-size: 10px; }
            .offcanvas-chat-toggle { position: fixed; bottom: 1.25rem; right: 1.25rem; z-index: 1055; }
            footer{background:var(--nav);color:#fff;padding:1rem 0;}
            .card{border-radius:6px;}
            @media (max-width: 991px) {
                .nav-item .nav-link { padding-left: .25rem; padding-right: .25rem; }
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <i class="bi bi-mortarboard-fill me-2"></i>
                    AlumniEcho
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navMain">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/"><i class="bi bi-house-door-fill me-1"></i> Accueil</a>
                        </li>
                        <li class="nav-item"><a class="nav-link {{ request()->is('web/utilisateurs*') ? 'active' : '' }}" href="/web/utilisateurs"><i class="bi bi-people-fill me-1"></i> Utilisateurs</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->is('web/souvenirs*') ? 'active' : '' }}" href="/web/souvenirs"><i class="bi bi-images me-1"></i> Souvenirs</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->is('web/temoignages*') ? 'active' : '' }}" href="/web/temoignages"><i class="bi bi-chat-left-text-fill me-1"></i> Témoignages</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->is('web/commentaires*') ? 'active' : '' }}" href="/web/commentaires"><i class="bi bi-chat-dots-fill me-1"></i> Commentaires</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->is('web/filieres*') ? 'active' : '' }}" href="/web/filieres"><i class="bi bi-diagram-3-fill me-1"></i> Filières</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->is('web/promotions*') ? 'active' : '' }}" href="/web/promotions"><i class="bi bi-calendar-event-fill me-1"></i> Promotions</a></li>
                        <li class="nav-item dropdown d-none d-lg-block"><a class="nav-link dropdown-toggle {{ request()->is('web/chatbot_faqs*') || request()->is('web/message_chatbots*') ? 'active' : '' }}" href="#" id="navChatbot" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-robot-fill me-1"></i> Chatbot
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navChatbot">
                                <li><a class="dropdown-item" href="/web/chatbot_faqs">FAQ Chatbot</a></li>
                                <li><a class="dropdown-item" href="/web/message_chatbots">Messages Chatbot</a></li>
                            </ul>
                        </li>
                    </ul>

                    <!-- Notifications + User -->
                    <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                        <li class="nav-item me-2 position-relative">
                            <a class="nav-link text-white" href="/notifications" title="Notifications">
                                <i class="bi bi-bell-fill" style="font-size:1.1rem"></i>
                                <span class="badge bg-danger rounded-pill badge-notif">3</span>
                            </a>
                        </li>
                        <!-- Chatbot quick open (desktop hidden on small screens) -->
                        <li class="nav-item d-none d-lg-block me-2">
                            <button class="btn btn-outline-light btn-sm" data-bs-toggle="offcanvas" data-bs-target="#chatbotSidebar" aria-controls="chatbotSidebar">
                                <i class="bi bi-chat-dots me-1"></i> Chat
                            </button>
                        </li>
                        <!-- User dropdown -->
                        @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1" style="font-size:1.15rem"></i>
                                <span class="d-none d-lg-inline">{{ auth()->user()->nom_user ?? auth()->user()->name ?? 'Mon compte' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="/settings"><i class="bi bi-gear me-2"></i> Paramètres</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i> Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm text-white" href="/login"><i class="bi bi-box-arrow-in-right me-1"></i> Connexion</a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        @if(request()->is('/'))
        <header class="hero-section text-center" style="padding:44px 0;border-bottom:1px solid rgba(0,0,0,0.04);background:linear-gradient(180deg,#ffffff 0%,#f7f9fb 100%);">
            <div class="container">
                <h1 class="display-5 fw-bold">Bienvenue sur AlumniEcho 🎓</h1>
                <p class="lead text-muted">La plateforme qui connecte les anciens étudiants, leurs souvenirs et leurs témoignages.</p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
                    <a href="/web/promotions" class="btn btn-primary btn-lg"><i class="bi bi-calendar-event me-2"></i>Voir promotions</a>
                    <a href="/web/utilisateurs" class="btn btn-primary btn-lg px-4"><i class="bi bi-people"></i> Explorer les utilisateurs</a>
                    <a href="/web/filieres" class="btn btn-outline-secondary btn-lg"><i class="bi bi-diagram-3 me-2"></i>Filières</a>
                    <a href="/web/souvenirs" class="btn btn-outline-secondary btn-lg px-4"><i class="bi bi-images"></i> Voir les souvenirs</a>
                    <a href="/web/temoignages" class="btn btn-outline-secondary btn-lg px-4"><i class="bi bi-chat-left-text"></i> Lire les témoignages</a>
                </div>
            </div>
        </header>
        @endif

        <main class="container">
            @yield('main')
        </main>

        <!-- Chatbot offcanvas (accessible via button) -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="chatbotSidebar" aria-labelledby="chatbotSidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="chatbotSidebarLabel"><i class="bi bi-robot me-2"></i> Chatbot AlumniEcho</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column">
                <div id="chatbot-messages" class="mb-3" style="max-height:420px; overflow-y:auto;">
                    <div class="alert alert-secondary">👋 Bonjour ! Je suis Echo, votre assistant AlumniEcho.</div>
                </div>
                <form id="chatbot-form" class="mt-auto" onsubmit="return false;">
                    <div class="input-group">
                        <input id="chat-input" type="text" class="form-control" placeholder="Votre message..." aria-label="Votre message">
                        <button id="chat-send" class="btn btn-primary" type="button"><i class="bi bi-send"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <footer class="text-center">
            <div class="container">
                <small>&copy; 2026 AlumniEcho - Tous droits réservés.</small>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const chatMessages6 = document.getElementById('chatbot-messages');
            const chatInput6 = document.getElementById('chat-input');
            const chatSend6 = document.getElementById('chat-send');

            function appendChatBubble6(text, side = 'bot') {
                const wrapper = document.createElement('div');
                wrapper.className = `text-${side === 'user' ? 'end' : 'start'} mb-2`;
                wrapper.innerHTML = `<div class="d-inline-block bg-${side === 'user' ? 'primary text-white' : 'light text-dark'} p-2 rounded">${text}</div>`;
                chatMessages6?.appendChild(wrapper);
                if (chatMessages6) chatMessages6.scrollTop = chatMessages6.scrollHeight;
            }

            function setLoading6(loading) {
                let loadingEl = document.getElementById('chat-loading');
                if (loading && !loadingEl) {
                    loadingEl = document.createElement('div');
                    loadingEl.id = 'chat-loading';
                    loadingEl.className = 'text-start mb-2';
                    loadingEl.innerHTML = '<div class="d-inline-block bg-light text-dark p-2 rounded">En cours de réponse...</div>';
                    chatMessages6?.appendChild(loadingEl);
                    if (chatMessages6) chatMessages6.scrollTop = chatMessages6.scrollHeight;
                } else if (!loading && loadingEl) {
                    loadingEl.remove();
                }
            }

            async function sendSidebarMessage6() {
                const text = chatInput6?.value.trim();
                if (!text) return;

                appendChatBubble6(text, 'user');
                if (chatInput6) chatInput6.value = '';
                setLoading6(true);

                try {
                    const response = await fetch('/api/chatbot/ask', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({ question: text }),
                    });

                    const data = await response.json();
                    appendChatBubble6(data.reponse || data.error || 'Erreur lors de la requête.', 'bot');
                } catch (error) {
                    appendChatBubble6('Erreur de communication avec le serveur.', 'bot');
                } finally {
                    setLoading6(false);
                }
            }

            chatSend6?.addEventListener('click', sendSidebarMessage6);
            chatInput6?.addEventListener('keypress', e => { if (e.key === 'Enter') sendSidebarMessage6(); });
        </script>
    </body>
</html>
