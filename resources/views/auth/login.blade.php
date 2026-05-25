@extends('template')
@section('title', 'Connexion - AlumniEcho')

@section('styles')
<style>
    .auth-card { max-width: 460px; margin: 40px auto; }
    .auth-header { background: linear-gradient(135deg, #4C3B7F, #6B5BA8); color: white; padding: 28px; border-radius: 12px 12px 0 0; text-align: center; }
</style>
@endsection

@section('main')
<div class="auth-card">
    <div class="auth-header">
        <i class="bi bi-mortarboard-fill fs-1 mb-2 d-block"></i>
        <h4 class="fw-bold mb-1">AlumniEcho</h4>
        <p class="opacity-75 mb-0 small">Connectez-vous à votre espace</p>
    </div>
    <div class="card border-0 shadow" style="border-radius:0 0 12px 12px;">
        <div class="card-body p-4">
            @if(session('error'))
            <div class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger py-2">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            @endif
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Identifiant</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" name="login_user" value="{{ old('login_user') }}"
                               placeholder="Votre identifiant" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" name="password_user" placeholder="••••••••" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </button>
            </form>
            <hr class="my-3">
            <p class="text-center text-muted mb-0 small">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="fw-semibold">S'inscrire</a>
            </p>
            <div class="mt-3 p-3 rounded-2 bg-light small text-muted">
                <strong>Comptes de test :</strong><br>
                admin / admin123 &nbsp;|&nbsp; alumni / alumni123 &nbsp;|&nbsp; visiteur / visiteur123
            </div>
        </div>
    </div>
</div>
@endsection
