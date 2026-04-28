<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: window.innerWidth > 960 }"
      x-init="window.addEventListener('resize', () => { sidebarOpen = window.innerWidth > 960; })">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — SeatLookFor</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }

        /* ── Admin shell ── */
        .admin-shell {
            display: flex;
            min-height: 100vh;
            background: var(--bg);
        }

        /* ── Sidebar ── */
        .admin-sidebar {
            width: 240px;
            flex-shrink: 0;
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform 0.2s ease;
        }
        .admin-sidebar__logo {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
        }
        .admin-sidebar__logo a {
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }
        .admin-sidebar__user {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .admin-sidebar__avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.9rem; color: #fff;
            flex-shrink: 0;
        }
        .admin-sidebar__name { font-size: 0.85rem; font-weight: 600; color: var(--text); }
        .admin-sidebar__role { font-size: 0.75rem; color: var(--text-dim); }

        .admin-sidebar__nav { padding: 16px 12px; flex: 1; display: flex; flex-direction: column; gap: 4px; }
        .admin-nav-link {
            display: block;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-dim);
            transition: background 0.15s, color 0.15s;
        }
        .admin-nav-link:hover { background: var(--bg-raised); color: var(--text); }
        .admin-nav-link--active { background: rgba(128,0,32,0.25); color: var(--primary); font-weight: 600; border-left: 2px solid var(--primary); }

        .admin-sidebar__footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
            display: flex; flex-direction: column; gap: 8px;
        }
        .admin-sidebar__footer a,
        .admin-sidebar__footer button {
            display: block; width: 100%; text-align: left;
            padding: 10px 14px; border-radius: 8px;
            font-size: 0.875rem; font-weight: 500;
            color: var(--text-dim);
            background: none; border: none; cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }
        .admin-sidebar__footer a:hover,
        .admin-sidebar__footer button:hover { background: var(--bg-raised); color: var(--text); }
        .admin-sidebar__footer .logout-btn:hover { color: #f87171; background: rgba(239,68,68,0.08); }

        /* ── Main ── */
        .admin-main {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .admin-topbar {
            height: 56px;
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 12px;
            position: sticky; top: 0; z-index: 50;
        }
        .admin-topbar__toggle {
            display: none;
            background: none; border: none; cursor: pointer;
            color: var(--text-dim); font-size: 1.2rem; padding: 4px;
        }
        .admin-content {
            flex: 1;
            padding: 32px 28px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }

        /* ── Overlay for mobile ── */
        .admin-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 99;
            background: rgba(0,0,0,0.5);
        }

        @media (max-width: 960px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar--open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .admin-topbar__toggle { display: block; }
            .admin-overlay--show { display: block; }
            .admin-content { padding: 20px 16px; }
        }

        /* ── Demo banner ── */
        .demo-banner {
            background: linear-gradient(90deg, #78350f, #92400e);
            color: #fef3c7;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 7px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.3px;
            border-bottom: 1px solid rgba(251,191,36,0.25);
        }
        .demo-banner__timer {
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            font-weight: 700;
            color: #fbbf24;
            background: rgba(0,0,0,0.3);
            padding: 1px 10px;
            border-radius: 4px;
            min-width: 52px;
            text-align: center;
            letter-spacing: 1px;
        }

        /* ── Tailwind overrides for dark admin theme ── */
        .admin-content .text-gray-800 { color: #f1f5f9 !important; }
        .admin-content .text-gray-700 { color: #e2e8f0 !important; }
        .admin-content .text-gray-600 { color: #cbd5e1 !important; }
        .admin-content .text-gray-500 { color: #94a3b8 !important; }
        .admin-content .text-gray-400 { color: #64748b !important; }
        .admin-content .bg-white { background: var(--bg-card) !important; }
        .admin-content .shadow-md,
        .admin-content .shadow-lg { box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important; }
    </style>
</head>
<body>
<div class="admin-shell">

    <!-- Sidebar -->
    <aside class="admin-sidebar" :class="{ 'admin-sidebar--open': sidebarOpen }">
        <div class="admin-sidebar__logo">
            <a href="{{ route('home') }}">SeatLookFor</a>
            <div style="font-size:0.7rem;color:var(--text-dim);margin-top:2px;text-transform:uppercase;letter-spacing:1px;">Panel Admin</div>
        </div>

        <div class="admin-sidebar__user">
            <div class="admin-sidebar__avatar">{{ substr(Auth::user()->nombre ?? 'U', 0, 1) }}</div>
            <div>
                <div class="admin-sidebar__name">{{ Auth::user()->nombre ?? 'Admin' }}</div>
                <div class="admin-sidebar__role">{{ Auth::user()->es_demo ? 'Usuario Demo' : 'Administrador' }}</div>
            </div>
        </div>

        <nav class="admin-sidebar__nav">
            <a href="{{ route('dashboard') }}" class="admin-nav-link {{ request()->routeIs('dashboard') ? 'admin-nav-link--active' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('establecimiento.listado') }}" class="admin-nav-link {{ request()->routeIs('establecimiento.*') ? 'admin-nav-link--active' : '' }}">
                Establecimientos
            </a>
            <a href="{{ route('eventos.listado') }}" class="admin-nav-link {{ request()->routeIs('eventos.*') ? 'admin-nav-link--active' : '' }}">
                Eventos
            </a>
        </nav>

        <div class="admin-sidebar__footer">
            <a href="{{ route('home') }}">Modo Usuario</a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;padding:0;">
                @csrf
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <!-- Mobile overlay -->
    <div class="admin-overlay" :class="{ 'admin-overlay--show': sidebarOpen && window.innerWidth <= 960 }"
         @click="sidebarOpen = false"></div>

    <!-- Main -->
    <div class="admin-main">
        <header class="admin-topbar">
            <button class="admin-topbar__toggle" @click="sidebarOpen = !sidebarOpen">☰</button>
            <span style="font-size:0.85rem;color:var(--text-dim);margin-left:auto;">
                {{ Auth::user()->nombre ?? '' }} {{ Auth::user()->apellido ?? '' }}
            </span>
        </header>

        @if(Auth::check() && Auth::user()->es_demo && Auth::user()->demo_expires_at)
        <div class="demo-banner">
            <span>⚠ Modo Demo — el contenido se reseteará en</span>
            <span class="demo-banner__timer" id="demo-countdown">15:00</span>
        </div>
        <script>window._demoExpiresAt = {{ Auth::user()->demo_expires_at->timestamp }};</script>
        @endif

        <main class="admin-content">
            {{ $slot }}
        </main>
    </div>

</div>
<script>
(function () {
    var el = document.getElementById('demo-countdown');
    if (!el || !window._demoExpiresAt) return;

    var expired = false;

    function pad(n) { return n < 10 ? '0' + n : String(n); }

    function tick() {
        var remaining = Math.max(0, window._demoExpiresAt - Math.floor(Date.now() / 1000));
        el.textContent = pad(Math.floor(remaining / 60)) + ':' + pad(remaining % 60);

        if (remaining === 0 && !expired) {
            expired = true;
            el.textContent = '00:00';
            // Notifica al servidor y redirige al login
            fetch('{{ route("demo.session.expired") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).finally(function () {
                window.location.href = '{{ route("admin.login.form") }}';
            });
        }
    }

    tick();
    setInterval(tick, 1000);
})();
</script>
</body>
</html>
