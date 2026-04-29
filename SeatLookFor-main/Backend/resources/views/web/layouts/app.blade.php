<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SeatLookFor')</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

    <nav class="navbar" x-data="{ userMenuOpen: false }">
        <span class="navbar__logo">
            <a href="{{ route('home') }}">SeatLookFor</a>
        </span>

        <div class="navbar__search">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-right:8px;">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" class="navbar__search-input" placeholder="Buscar eventos...">
        </div>

        <div class="navbar__menu">
            <div class="navbar__menu-item">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
            </div>
            <div class="navbar__menu-item">
                <a href="{{ route('eventos.index') }}" class="nav-link">Eventos</a>
            </div>
            <div class="navbar__menu-item">
                <a href="{{ route('about') }}" class="nav-link">Sobre SLF</a>
            </div>

            @auth
                @if(Auth::user()->admin)
                <a href="{{ route('dashboard') }}" class="navbar__admin-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0;">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                    </svg>
                    Panel Admin
                </a>
                @endif
            @endauth

            <div class="navbar__user">
                <svg class="navbar__user-icon"
                     xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 24 24"
                     @click="userMenuOpen = !userMenuOpen"
                     style="cursor:pointer;">
                    <path fill="#f59e0b" d="M12 4a4 4 0 0 1 4 4 4 4 0 0 1-4 4 4 4 0 0 1-4-4 4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
                </svg>

                <div class="navbar__dropdown" :class="{ 'navbar__dropdown--show': userMenuOpen }">
                    @auth
                        <a href="{{ route('usuario.perfil') }}" class="navbar__dropdown-item">
                            <span class="navbar__dropdown-text">{{ Auth::user()->nombre }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="navbar__dropdown-item">
                                <span class="navbar__dropdown-text">Cerrar Sesión</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="navbar__dropdown-item">
                            <span class="navbar__dropdown-text">Iniciar Sesión</span>
                        </a>
                        <a href="{{ route('registro') }}" class="navbar__dropdown-item">
                            <span class="navbar__dropdown-text">Registrarse</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="flash-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flash-error">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')

    <footer class="footer">
        <div class="footer__content">
            <nav class="footer__nav">
                <div class="footer__section">
                    <h2 class="footer__title">SeatLookFor</h2>
                    <p style="font-size:13px;color:var(--text-dim);line-height:1.7;max-width:200px;">
                        Tu plataforma para reservar asientos en teatros, salas y eventos locales.
                    </p>
                </div>
                <div class="footer__section">
                    <h2 class="footer__title">Navegación</h2>
                    <ul style="display:flex;flex-direction:column;gap:10px;">
                        <li><a href="{{ route('home') }}" style="font-size:13px;color:var(--text-dim);transition:color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-dim)'">Inicio</a></li>
                        <li><a href="{{ route('eventos.index') }}" style="font-size:13px;color:var(--text-dim);transition:color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-dim)'">Eventos</a></li>
                        <li><a href="{{ route('about') }}" style="font-size:13px;color:var(--text-dim);transition:color 0.2s;" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-dim)'">Sobre nosotros</a></li>
                    </ul>
                </div>
                <div class="footer__section">
                    <h2 class="footer__title">Encuéntranos</h2>
                    <div class="footer__social" style="justify-content:flex-start;margin-bottom:0;">
                        {{-- Instagram --}}
                        <a href="#" class="footer__social-link" title="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        {{-- LinkedIn --}}
                        <a href="{{ route('about') }}" class="footer__social-link" title="LinkedIn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </a>
                        {{-- Twitter/X --}}
                        <a href="#" class="footer__social-link" title="Twitter / X">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        {{-- Facebook --}}
                        <a href="#" class="footer__social-link" title="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </nav>
            <div class="footer__divider"></div>
            <p class="footer__copy">© {{ date('Y') }} SeatLookFor · Todos los derechos reservados</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
