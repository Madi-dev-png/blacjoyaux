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
        /* ============================================================
           BLAC JOYAUX — Back-office admin
           Palette dédiée "Luxe lavande" : violet profond + lilas clair,
           surchargée en local sur .admin-shell pour ne pas impacter la boutique.
           ============================================================ */
        body { background: #FBF6FD; }

        .admin-shell {
            --aubergine:   #4B2E6B;   /* accent principal (boutons, titres) */
            --aubergine-2: #6D3FA0;   /* hover */
            --or:          #8B6BAE;   /* accent secondaire */
            --or-clair:    #C9AEE0;
            --ivoire:      #FFFFFF;
            --ivoire-2:    #EEE4F7;
            --gris:        #8B7D99;
            --blanc:       #FFFFFF;
            --terre:       #6D3FA0;
            --vert-jade:   #1FAE71;
            --bad:         #E0475B;

            --adm-sidebar:    #1E1626;
            --adm-sidebar-2:  #2A2033;
            --adm-page:       #FBF6FD;
            --adm-purple:     #755A9D;
            --adm-purple-2:   #4B2E6B;
            --adm-purple-soft:#F1E9FA;

            display: grid; grid-template-columns: 250px 1fr; min-height: 100vh;
            background: var(--adm-page);
        }

        /* ---------- Sidebar ---------- */
        .admin-side { background: var(--adm-sidebar); color: #D9CCEC; padding: 1.6rem 1.2rem; display: flex; flex-direction: column; }
        .admin-side .brand { margin-bottom: 2.2rem; display: flex; align-items: center; gap: .6rem; }
        .admin-side .brand-mark { width: 30px; height: 30px; border-radius: 9px; background: linear-gradient(135deg, var(--adm-purple), #A98BC7); display: inline-flex; align-items: center; justify-content: center; color: #fff; font-family: var(--font-display); font-size: 1rem; }
        .admin-side .brand-name { color: #fff; font-family: var(--font-display); font-size: 1.1rem; letter-spacing: .03em; }
        .admin-nav { display: grid; gap: .25rem; }
        .admin-nav a { color: #B7A8CB; padding: .68rem .9rem; border-radius: 10px; font-size: .9rem; display: flex; align-items: center; gap: .7rem; transition: background .15s, color .15s; }
        .admin-nav a svg { flex-shrink: 0; opacity: .85; }
        .admin-nav a:hover { background: rgba(255,255,255,.06); color: #fff; }
        .admin-nav a.is-active { background: var(--adm-purple); color: #fff; box-shadow: 0 6px 16px -6px rgba(117,90,157,.7); }
        .admin-nav a.is-active svg { opacity: 1; }
        .admin-side-foot { margin-top: auto; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,.1); font-size: .82rem; display: grid; gap: .7rem; }
        .admin-side-foot a, .admin-side-foot button { color: #B7A8CB; display: flex; align-items: center; gap: .5rem; }
        .admin-side-foot a:hover, .admin-side-foot button:hover { color: #fff; }

        /* ---------- Topbar ---------- */
        .admin-main { padding: 2rem 2.5rem; }
        .admin-topbar { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.8rem; gap: 1rem; flex-wrap: wrap; }
        .admin-topbar h1 { font-size: 1.8rem; margin: 0; color: #2A1B3D; }
        .admin-topbar .subtitle { color: var(--gris); font-size: .92rem; margin-top: .2rem; }
        .admin-topbar-right { display: flex; align-items: center; gap: .8rem; }
        .admin-search { display: none; }
        @media (min-width: 900px) {
            .admin-search { display: flex; align-items: center; gap: .5rem; background: #fff; border: 1px solid var(--ivoire-2); border-radius: 100px; padding: .55rem 1rem; color: var(--gris); font-size: .85rem; min-width: 220px; box-shadow: var(--shadow-sm); }
            .admin-search svg { color: var(--gris); flex-shrink: 0; }
        }
        .admin-bell { width: 40px; height: 40px; border-radius: 50%; background: #fff; display: inline-flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); color: var(--adm-purple-2); flex-shrink: 0; }

        /* ---------- Stat cards ---------- */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.2rem; margin-bottom: 2rem; }
        .stat-card { background: var(--blanc); border-radius: 18px; padding: 1.4rem; box-shadow: var(--shadow-sm); }
        .stat-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.1rem; }
        .stat-card-ico { width: 40px; height: 40px; border-radius: 12px; background: var(--adm-purple-soft); color: var(--adm-purple-2); display: inline-flex; align-items: center; justify-content: center; }
        .stat-trend { font-size: .72rem; font-weight: 600; padding: .22rem .55rem; border-radius: 100px; background: #E4F7EE; color: var(--vert-jade); }
        .stat-trend.is-down { background: #FBE7EA; color: var(--bad); }
        .stat-trend.is-flat { background: var(--ivoire-2); color: var(--gris); }
        .stat-card .label { font-size: .78rem; color: var(--gris); text-transform: uppercase; letter-spacing: .07em; }
        .stat-card .value { font-family: var(--font-display); font-size: 1.7rem; color: #2A1B3D; margin-top: .25rem; }

        /* ---------- Panels & tables ---------- */
        .panel { background: var(--blanc); border-radius: 18px; padding: 1.6rem; box-shadow: var(--shadow-sm); margin-bottom: 1.6rem; }
        .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: .4rem; }
        .panel h2 { font-size: 1.15rem; margin-top: 0; color: #2A1B3D; }
        table.data { width: 100%; border-collapse: collapse; font-size: .88rem; }
        table.data th, table.data td { text-align: left; padding: .8rem .6rem; border-bottom: 1px solid var(--ivoire-2); }
        table.data th { font-size: .74rem; text-transform: uppercase; letter-spacing: .06em; color: var(--gris); font-weight: 600; }
        table.data tbody tr:hover { background: #FBF8FE; }
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-scroll table.data { min-width: 560px; }
        .status-pill { font-size: .74rem; padding: .28rem .75rem; border-radius: 100px; font-weight: 600; white-space: nowrap; }
        .st-en_attente { background:#FDF1D8; color:#B2790E; }
        .st-confirmee  { background:#DCEEFB; color:#1D6FA5; }
        .st-expediee   { background:#EDE1FA; color:#6D3FA0; }
        .st-livree     { background:#DDF5EA; color:#12915F; }
        .st-annulee    { background:#FBE0E4; color:#C43A4C; }
        .seo-meter { height: 8px; border-radius: 100px; background: var(--ivoire-2); overflow: hidden; }
        .seo-meter span { display: block; height: 100%; transition: width .3s, background .3s; }
        .admin-grid-2 { display: grid; grid-template-columns: 1.4fr 1fr; gap: 1.6rem; align-items: start; }

        /* ---------- Carte d'aide (gradient) ---------- */
        .admin-help-card {
            border-radius: 18px; padding: 1.6rem; color: #fff;
            background: linear-gradient(150deg, var(--adm-purple-2), var(--adm-purple));
            box-shadow: 0 16px 32px -16px rgba(75,46,107,.55);
        }
        .admin-help-card h3 { color: #fff; font-size: 1.2rem; margin: 0 0 .5rem; }
        .admin-help-card p { color: #E4D9F2; font-size: .88rem; margin: 0 0 1.1rem; line-height: 1.5; }
        .admin-help-card a { display: inline-flex; align-items: center; gap: .5rem; background: #fff; color: var(--adm-purple-2); padding: .65rem 1.2rem; border-radius: 100px; font-size: .85rem; font-weight: 600; }
        .admin-help-card a:hover { background: #F1E9FA; color: var(--adm-purple-2); }

        .ranking-row { display: flex; align-items: center; gap: .9rem; padding: .7rem 0; border-bottom: 1px solid var(--ivoire-2); }
        .ranking-row:last-child { border-bottom: none; }
        .ranking-num { width: 26px; height: 26px; border-radius: 50%; background: var(--adm-purple-soft); color: var(--adm-purple-2); font-size: .78rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .ranking-name { font-size: .88rem; color: #2A1B3D; font-weight: 500; }
        .ranking-sub { font-size: .76rem; color: var(--gris); }
        .ranking-value { margin-left: auto; text-align: right; font-size: .88rem; font-weight: 600; color: var(--adm-purple-2); }

        @media (max-width: 900px) {
            .admin-shell { grid-template-columns: 1fr; }
            .admin-side { flex-direction: row; flex-wrap: wrap; align-items: center; gap: .4rem .8rem; padding: 1rem 1.2rem; }
            .admin-side .brand { margin-bottom: 0; margin-right: auto; }
            .admin-nav { grid-auto-flow: column; gap: .2rem; }
            .admin-nav a { padding: .5rem .7rem; font-size: .85rem; }
            .admin-nav a span.nav-label { display: none; }
            .admin-side-foot { margin-top: 0; padding-top: 0; border-top: none; width: 100%; flex-direction: row; gap: 1.2rem; }
            .admin-main { padding: 1.4rem 1.2rem; }
            .admin-grid-2 { grid-template-columns: 1fr; }
            .admin-topbar h1 { font-size: 1.4rem; }
        }
        @media (max-width: 560px) {
            .admin-nav { grid-auto-flow: row; width: 100%; }
            .admin-nav a { font-size: .9rem; }
            .admin-nav a span.nav-label { display: inline; }
            .stat-grid { grid-template-columns: 1fr 1fr; }
            .admin-topbar-right { width: 100%; justify-content: flex-end; }
        }
        @media (max-width: 400px) {
            .stat-grid { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="admin-shell">
    <aside class="admin-side">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <span class="brand-mark">B</span>
            <span class="brand-name">Blac Joyaux</span>
        </a>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                <span class="nav-label">Tableau de bord</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.9 7.5 12 2 3.1 7.5"/><path d="M3.1 7.5 12 13l8.9-5.5"/><path d="M12 13v9"/><path d="M3.1 7.5v9L12 22l8.9-5.5v-9"/></svg>
                <span class="nav-label">Produits</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <span class="nav-label">Commandes</span>
            </a>
            <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'is-active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.82 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                <span class="nav-label">FAQ</span>
            </a>
        </nav>
        <div class="admin-side-foot">
            <a href="{{ route('home') }}" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14 21 3"/></svg>
                Voir la boutique
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;font-family:var(--font-body);font-size:.82rem;padding:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                    Déconnexion
                </button>
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