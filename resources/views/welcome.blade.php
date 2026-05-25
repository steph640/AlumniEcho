@extends('template')
@section('title', 'AlumniEcho — Mémoires de Promotions')

@section('styles')
<style>
    .hero { background: linear-gradient(135deg, #4C3B7F 0%, #6B5BA8 60%, #2D5016 100%); color: white; padding: 70px 0 50px; }
    .hero h1 { font-size: 2.8rem; font-weight: 800; }
    .hero p { font-size: 1.15rem; opacity: .88; }
    .stat-card { background: white; border-radius: 12px; padding: 24px; text-align: center; box-shadow: 0 2px 12px rgba(76,59,127,.1); }
    .stat-card .number { font-size: 2.2rem; font-weight: 800; color: #4C3B7F; }
    .section-title { font-weight: 700; color: #4C3B7F; position: relative; margin-bottom: 28px; }
    .section-title::after { content:''; display:block; width:50px; height:3px; background:#D4AF37; margin-top:8px; }
    .souvenir-card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.08); transition: transform .2s, box-shadow .2s; overflow: hidden; }
    .souvenir-card:hover { transform: translateY(-5px); box-shadow: 0 8px 24px rgba(76,59,127,.18); }
    .souvenir-card .card-img-top { height: 160px; object-fit: cover; }
    .photo-placeholder { height: 160px; background: linear-gradient(135deg,#e8e4f5,#f0ecfb); display:flex;align-items:center;justify-content:center;font-size:2.5rem; }
    .tem-card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.08); padding: 24px; position: relative; }
    .tem-card::before { content:'"'; font-size: 5rem; color: #e0d8f5; position: absolute; top: -10px; left: 16px; line-height: 1; }
    .badge-gold { background: #D4AF37; color: #1a1a1a; }
    .cta-section { background: linear-gradient(135deg, #4C3B7F, #6B5BA8); color: white; border-radius: 16px; padding: 48px 32px; }
</style>
@endsection

@section('main')

{{-- ===== HERO ===== --}}
<div class="hero rounded-3 mb-5 px-4">
    <div class="row align-items-center">
        <div class="col-lg-7">
            <span class="badge badge-gold mb-3 px-3 py-2 fs-6">🎓 Plateforme des Anciens Étudiants</span>
            <h1>Revivez vos plus beaux<br><span style="color:#D4AF37">souvenirs de promotion</span></h1>
            <p class="mt-3 mb-4">AlumniEcho rassemble les mémoires, photos et témoignages de toutes les promotions. Rejoignez la communauté et partagez votre histoire.</p>
            @guest
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold px-4" style="color:#1a1a1a">
                    <i class="bi bi-person-plus me-2"></i>Rejoindre la communauté
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </a>
            </div>
            @endguest
            @auth
            <a href="{{ auth()->user()->role_user === 'admin' ? route('admin.dashboard') : (auth()->user()->role_user === 'alumni' ? route('alumni.dashboard') : route('visiteur.dashboard')) }}"
               class="btn btn-warning btn-lg fw-bold px-4" style="color:#1a1a1a">
                <i class="bi bi-speedometer2 me-2"></i>Mon tableau de bord
            </a>
            @endauth
        </div>
        <div class="col-lg-5 text-center mt-4 mt-lg-0">
            <div style="font-size:8rem;opacity:.3">🎓</div>
        </div>
    </div>
</div>

{{-- ===== STATS ===== --}}
<div class="row g-3 mb-5">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="number">{{ $promotions->count() }}</div>
            <div class="text-muted small">Promotions</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="number">{{ $souvenirs->count() }}</div>
            <div class="text-muted small">Souvenirs</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="number">{{ $temoignages->count() }}</div>
            <div class="text-muted small">Témoignages</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="number">∞</div>
            <div class="text-muted small">Souvenirs partagés</div>
        </div>
    </div>
</div>

{{-- ===== PROMOTIONS ===== --}}
@if($promotions->count())
<section class="mb-5">
    <h2 class="section-title"><i class="bi bi-calendar-event me-2"></i>Dernières Promotions</h2>
    <div class="row g-3">
        @foreach($promotions as $promo)
        <div class="col-6 col-md-3">
            <div class="card text-center h-100 border-0 shadow-sm" style="border-radius:12px;">
                <div class="card-body py-3">
                    <div style="font-size:2rem">🎓</div>
                    <h6 class="fw-bold mb-1">{{ $promo->nom_promo }}</h6>
                    <span class="badge badge-gold">{{ $promo->annee_promo }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== SOUVENIRS ===== --}}
@if($souvenirs->count())
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title mb-0"><i class="bi bi-images me-2"></i>Derniers Souvenirs</h2>
        @auth
        <a href="{{ auth()->user()->role_user === 'admin' ? route('admin.souvenirs.index') : (auth()->user()->role_user === 'alumni' ? route('alumni.souvenirs.index') : route('visiteur.souvenirs.index')) }}"
           class="btn btn-outline-primary btn-sm">Voir tout</a>
        @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Voir tout</a>
        @endauth
    </div>
    <div class="row g-4">
        @foreach($souvenirs as $souv)
        <div class="col-md-6 col-lg-3">
            <div class="card souvenir-card h-100">
                @if($souv->url_photo_souv)
                    <img src="{{ $souv->url_photo_souv }}" class="card-img-top" alt="{{ $souv->titre_souv }}">
                @else
                    <div class="photo-placeholder"><i class="bi bi-image text-muted"></i></div>
                @endif
                <div class="card-body">
                    <h6 class="fw-bold">{{ $souv->titre_souv }}</h6>
                    <p class="text-muted small mb-0">{{ Str::limit($souv->description_souv, 80) }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== TÉMOIGNAGES ===== --}}
@if($temoignages->count())
<section class="mb-5">
    <h2 class="section-title"><i class="bi bi-chat-quote me-2"></i>Témoignages d'Alumni</h2>
    <div class="row g-4">
        @foreach($temoignages as $tem)
        <div class="col-md-4">
            <div class="card tem-card h-100">
                <p class="mt-4 mb-3" style="line-height:1.7">{{ Str::limit($tem->contenu_tem, 180) }}</p>
                <div class="mt-auto border-top pt-2">
                    @if($tem->utilisateur)
                    <small class="fw-semibold text-primary">{{ $tem->utilisateur->prenom_user }} {{ $tem->utilisateur->nom_user }}</small>
                    @endif
                    @if($tem->promotion)
                    <br><small class="text-muted">{{ $tem->promotion->nom_promo }}</small>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== CTA ===== --}}
@guest
<div class="cta-section text-center mb-4">
    <h3 class="fw-bold mb-2">Vous êtes un ancien étudiant ?</h3>
    <p class="mb-4 opacity-75">Inscrivez-vous gratuitement et partagez vos souvenirs avec toute la communauté.</p>
    <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold px-5" style="color:#1a1a1a">
        <i class="bi bi-person-plus me-2"></i>Créer mon compte
    </a>
</div>
@endguest

@endsection
