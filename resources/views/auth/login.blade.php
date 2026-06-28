<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Administration Blac Joyaux</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .login-wrap { min-height: 100vh; display: grid; place-items: center; padding: 2rem;
            background: radial-gradient(120% 100% at 80% 0%, rgba(200,144,47,.18), transparent 60%), linear-gradient(180deg, var(--ivoire), var(--ivoire-2)); }
        .login-card { background: var(--blanc); border-radius: var(--r-lg); box-shadow: var(--shadow);
            padding: 2.5rem; width: min(420px, 100%); }
        .login-card .brand { justify-content: center; margin-bottom: .5rem; }
        .login-card h1 { text-align: center; font-size: 1.6rem; }
        .login-sub { text-align: center; color: var(--gris); font-size: .9rem; margin-bottom: 1.8rem; }
        .login-error { background: #F7E4E6; color: var(--bad); padding: .7rem 1rem; border-radius: var(--r-sm); font-size: .88rem; margin-bottom: 1rem; }
        .login-hint { text-align:center; font-size:.8rem; color:var(--gris); margin-top:1.2rem; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <a href="{{ route('home') }}" class="brand"><span class="brand-mark">◈</span><span class="brand-name">Blac Joyaux</span></a>
        <h1>Espace administration</h1>
        <p class="login-sub">Connectez-vous pour gérer la boutique</p>

        @if($errors->any())
            <div class="login-error">{{ $errors->first() }}</div>
        @endif
        @if(session('error'))
            <div class="login-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus style="width:100%;">
            </div>
            <div class="field">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required style="width:100%;">
            </div>
            <label style="display:flex; gap:.5rem; align-items:center; font-size:.88rem; color:var(--gris); margin-bottom:1.2rem;">
                <input type="checkbox" name="remember"> Se souvenir de moi
            </label>
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>

        <p class="login-hint">Compte de démo : admin@blacjoyaux.com / password</p>
    </div>
</div>
</body>
</html>
