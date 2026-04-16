<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Projeto Ebooks')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f8fc;
            --bg-soft: #eef2ff;
            --ink: #17223b;
            --muted: #62708a;
            --line: rgba(23, 34, 59, 0.14);
            --card: rgba(255, 255, 255, 0.84);
            --brand: #0f766e;
            --brand-dark: #115e59;
            --accent: #b45309;
            --shadow: 0 20px 38px rgba(15, 23, 42, 0.12);
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            min-height: 100%;
        }

        body {
            color: var(--ink);
            font-family: "Space Grotesk", sans-serif;
            background:
                radial-gradient(ellipse at 10% 0%, #bfdbfe 0%, transparent 38%),
                radial-gradient(ellipse at 96% 18%, #fde68a 0%, transparent 36%),
                radial-gradient(ellipse at 50% 100%, #bbf7d0 0%, transparent 34%),
                var(--bg);
        }

        .texture {
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .18;
            background-image: radial-gradient(rgba(0, 0, 0, 0.14) 0.4px, transparent 0.4px);
            background-size: 4px 4px;
            mix-blend-mode: soft-light;
            z-index: 0;
        }

        .mobile-bar {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-bottom: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .mobile-title {
            font-family: "Sora", sans-serif;
            font-weight: 800;
            letter-spacing: .02em;
        }

        .menu-btn {
            border: 1px solid #8fa4c7;
            border-radius: 12px;
            background: #fff;
            color: #1e293b;
            padding: 8px 10px;
            font-weight: 700;
            cursor: pointer;
        }

        .app {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 290px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            height: 100vh;
            position: sticky;
            top: 0;
            overflow: auto;
            padding: 18px 14px;
            border-right: 1px solid var(--line);
            background: linear-gradient(170deg, rgba(255, 255, 255, 0.86), rgba(255, 255, 255, 0.74));
            backdrop-filter: blur(8px);
        }

        .brand {
            font-family: "Sora", sans-serif;
            font-weight: 800;
            font-size: 1.08rem;
            margin-bottom: 4px;
        }

        .sub {
            color: var(--muted);
            font-size: .82rem;
            margin-bottom: 16px;
        }

        .menu {
            display: grid;
            gap: 9px;
        }

        .menu a {
            text-decoration: none;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-weight: 700;
            color: #1f2937;
            transition: all .16s ease;
            background: rgba(255, 255, 255, 0.55);
        }

        .menu a:hover {
            transform: translateX(2px);
            border-color: #c7d2fe;
            background: #fff;
        }

        .menu a.active {
            border-color: #99f6e4;
            background: linear-gradient(145deg, #d1fae5, #ccfbf1);
            color: var(--brand-dark);
        }

        .sidebar-note {
            margin-top: 18px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            font-size: .81rem;
            color: var(--muted);
        }

        .sidebar-note strong {
            display: block;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .content {
            padding: 24px;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--card);
            backdrop-filter: blur(8px);
            box-shadow: var(--shadow);
            padding: 16px;
        }

        .grid {
            display: grid;
            gap: 12px;
        }

        h1, h2, h3 {
            font-family: "Sora", sans-serif;
            letter-spacing: -.01em;
        }

        @media (max-width: 980px) {
            .mobile-bar { display: flex; }
            .app { grid-template-columns: 1fr; }
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                z-index: 100;
                width: min(90vw, 330px);
                transform: translateX(-104%);
                transition: transform .2s ease;
                box-shadow: 0 26px 34px rgba(15, 23, 42, 0.24);
            }
            .sidebar.open { transform: translateX(0); }
            .content { padding: 14px; }
        }
    </style>
    @yield('head')
</head>
<body>
<div class="texture"></div>

<header class="mobile-bar">
    <div class="mobile-title">Projeto Ebooks</div>
    <button id="menuToggle" class="menu-btn" type="button">Menu</button>
</header>

<div class="app">
    <aside id="sidebar" class="sidebar">
        <div class="brand">Projeto Ebooks</div>
        <div class="sub">Publicacao e leitura digital</div>

        <nav class="menu">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') || request()->routeIs('root') ? 'active' : '' }}">Home</a>
            <a href="{{ route('acesso') }}" class="{{ request()->routeIs('acesso') ? 'active' : '' }}">Acesso</a>
            <a href="{{ route('perfil') }}" class="{{ request()->routeIs('perfil') ? 'active' : '' }}">Perfil</a>
            <a href="{{ route('livros.publicar') }}" class="{{ request()->routeIs('livros.publicar') ? 'active' : '' }}">Publicar Livro</a>
            <a href="{{ route('painel') }}" class="{{ request()->routeIs('painel') ? 'active' : '' }}">Painel</a>
            <a href="{{ route('ebooks.lista') }}" class="{{ request()->routeIs('ebooks.lista') ? 'active' : '' }}">Biblioteca</a>
            <a href="{{ route('processar.pdf') }}" class="{{ request()->routeIs('processar.pdf') ? 'active' : '' }}" target="_blank">PDF Exemplo</a>
        </nav>

        <div class="sidebar-note">
            <strong>Dica:</strong>
            faca login na pagina Acesso para gerar token e usar no Perfil e na Publicacao.
        </div>
    </aside>

    <main class="content">
        @yield('content')
    </main>
</div>

<script>
    (function () {
        const btn = document.getElementById('menuToggle');
        const side = document.getElementById('sidebar');
        if (btn && side) {
            btn.addEventListener('click', function () {
                side.classList.toggle('open');
            });
        }
    })();
</script>
@yield('scripts')
</body>
</html>

