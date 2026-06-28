<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration') — Blac Joyaux</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { background: var(--ivoire-2); }
        .admin-shell { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
        .admin-side { background: var(--aubergine); color: var(--ivoire); padding: 1.6rem 1.2rem; display: flex; flex-direction: column; }
        .admin-side .brand { margin-bottom: 2rem; }
        .admin-side .brand-name { color: var(--ivoire); }
        .admin-nav { display: grid; gap: .3rem; }
        .admin-nav a { color: var(--ivoire-2); padding: .7rem .9rem; border-radius: var(--r-sm); font-size: .93rem; display: flex; align-items: center; gap: .6rem; }
        .admin-nav a:hover, .admin-nav a.is-active { background: rgba(247,241,231,.12); color: var(--or-clair); }
        .admin-side-foot { margin-top: auto; padding-top: 1.5rem; border-top: 1px solid rgba(247,241,231,.15); font-size: .82rem; }
        .admin-side-foot a { color: var(--ivoire-2); }
        .admin-main { padding: 2rem 2.5rem; }
        .admin-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.8rem; }
        .admin-topbar h1 { font-size: 1.8rem; margin: 0; }
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.2rem; margin-bottom: 2rem; }
        .stat-card { background: var(--blanc); border-radius: var(--r-md); padding: 1.4rem; box-shadow: var(--shadow-sm); }
        .stat-card .label { font-size: .82rem; color: var(--gris); text-transform: uppercase; letter-spacing: .08em; }
        .stat-card .value { font-family: var(--font-display); font-size: 2rem; color: var(--aubergine); margin-top: .3rem; }
        .panel { background: var(--blanc); border-radius: var(--r-md); padding: 1.6rem; box-shadow: var(--shadow-sm); margin-bottom: 1.6rem; }
        .panel h2 { font-size: 1.2rem; margin-top: 0; }
        table.data { width: 100%; border-collapse: collapse; font-size: .9rem; }
        table.data th, table.data td { text-align: left; padding: .7rem .6rem; border-bottom: 1px solid var(--ivoire-2); }
        table.data th { font-size: .78rem; text-transform: uppercase; letter-spacing: .06em; color: var(--gris); font-weight: 600; }
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-scroll table.data { min-width: 560px; }
        .status-pill { font-size: .76rem; padding: .25rem .7rem; border-radius: 100px; font-weight: 500; }
        .st-en_attente { background:#FBF0DC; color: var(--or); }
        .st-confirmee { background:#E0EEF7; color:#2A6FA3; }
        .st-expediee { background:#EDE4F5; color:#6B43A0; }
        .st-livree { background:#E6F0EC; color: var(--vert-jade); }
        .st-annulee { background:#F7E4E6; color: var(--bad); }
        .seo-meter { height: 8px; border-radius: 100px; background: var(--ivoire-2); overflow: hidden; }
        .seo-meter span { display: block; height: 100%; transition: width .3s, background .3s; }
        .admin-grid-2 { display: grid; grid-template-columns: 1.4fr 1fr; gap: 1.6rem; align-items: start; }
        @media (max-width: 800px) {
            .admin-shell { grid-template-columns: 1fr; }
            .admin-side { flex-direction: row; flex-wrap: wrap; align-items: center; gap: .4rem .8rem; padding: 1rem 1.2rem; }
            .admin-side .brand { margin-bottom: 0; margin-right: auto; }
            .admin-nav { grid-auto-flow: column; gap: .2rem; }
            .admin-nav a { padding: .5rem .7rem; font-size: .85rem; }
            .admin-side-foot { margin-top: 0; padding-top: 0; border-top: none; width: 100%; display: flex; gap: 1.2rem; }
            .admin-main { padding: 1.4rem 1.2rem; }
            .admin-grid-2 { grid-template-columns: 1fr; }
            .admin-topbar h1 { font-size: 1.4rem; }
        }
        @media (max-width: 520px) {
            .admin-nav { grid-auto-flow: row; width: 100%; }
            .admin-nav a { font-size: .9rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="admin-shell">
    <aside class="admin-side">
        <a href="{{ route('admin.dashboard') }}" class="brand"><span class="brand-mark">◈</span><span class="brand-name">Blac Joyaux</span></a>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">◧ Tableau de bord</a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">◈ Produits</a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">🛍 Commandes</a>
            <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'is-active' : '' }}">❔ FAQ</a>
        </nav>
        <div class="admin-side-foot">
            <a href="{{ route('home') }}" target="_blank">↗ Voir la boutique</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:.6rem;">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--ivoire-2);cursor:pointer;font-family:var(--font-body);font-size:.82rem;padding:0;">⎋ Déconnexion</button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        @if(session('success'))
            <div class="flash flash-success" style="margin:0 0 1.2rem;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error" style="margin:0 0 1.2rem;">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</div>
@stack('scripts')
</body>
</html>
