@extends('web.layouts.app')

@section('title', 'Eventos - SeatLookFor')

@section('content')

<div class="eventos">

    {{-- ── Filter bar ── --}}
    <div class="eventos__header">
        <div class="eventos__filters">
            <div class="eventos__filters-container">
                <form method="GET" action="{{ route('eventos.index') }}" id="filtros-form">

                    <select name="tipo" onchange="document.getElementById('filtros-form').submit()">
                        <option value="">Todos los tipos</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t }}" {{ request('tipo') == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>

                    <select name="categoria" onchange="document.getElementById('filtros-form').submit()">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c }}" {{ request('categoria') == $c ? 'selected' : '' }}>
                                {{ $c }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Hidden filter state inputs --}}
                    <input type="hidden" name="duracion"    id="duracion-input"    value="{{ request('duracion') }}">
                    <input type="hidden" name="fecha_filtro" id="fecha-filtro-input" value="{{ request('fecha_filtro') }}">
                    <input type="hidden" name="orden"       id="orden-input"       value="{{ request('orden') }}">

                    <button type="button"
                            onclick="setFilter('duracion-input','corta')"
                            class="eventos__filters-button {{ request('duracion') === 'corta' ? 'eventos__filters-button--active' : '' }}">
                        Corta
                    </button>

                    <button type="button"
                            onclick="setFilter('duracion-input','larga')"
                            class="eventos__filters-button {{ request('duracion') === 'larga' ? 'eventos__filters-button--active' : '' }}">
                        Larga
                    </button>

                    <button type="button"
                            onclick="setFilter('fecha-filtro-input','hoy')"
                            class="eventos__filters-button {{ request('fecha_filtro') === 'hoy' ? 'eventos__filters-button--active' : '' }}">
                        Hoy
                    </button>

                    <button type="button"
                            onclick="setFilter('fecha-filtro-input','mañana')"
                            class="eventos__filters-button {{ request('fecha_filtro') === 'mañana' ? 'eventos__filters-button--active' : '' }}">
                        Mañana
                    </button>

                    <button type="button"
                            onclick="setFilter('orden-input','valoracion')"
                            class="eventos__filters-button {{ request('orden') === 'valoracion' ? 'eventos__filters-button--active' : '' }}">
                        Por valoración
                    </button>

                    <a href="{{ route('eventos.index') }}"
                       class="eventos__filters-button"
                       style="text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18M6 12h12M10 18h4"/>
                        </svg>
                        Reset
                    </a>

                </form>
            </div>
        </div>
    </div>

    {{-- ── Events list ── --}}
    <div class="eventos__container">
        @if($eventos->isEmpty())
            <div class="eventos__no-results">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                     fill="none" stroke="var(--text-dim)" stroke-width="1.5" stroke-linecap="round"
                     stroke-linejoin="round" style="margin:0 auto 16px;display:block;">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <p>No se encontraron eventos que coincidan con los filtros.</p>
            </div>
        @else
            @foreach($eventos as $evento)
                <div class="event-card">
                    @if($evento->portada)
                        <img class="event-card__image"
                             src="{{ $evento->portada }}"
                             alt="{{ $evento->titulo }}">
                    @else
                        <div class="event-card__image"
                             style="background:linear-gradient(135deg,#4c1d95,#1e1b4b);display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                 fill="none" stroke="rgba(139,92,246,0.5)" stroke-width="1.5"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                        </div>
                    @endif

                    <div class="event-card__title">
                        <p>{{ $evento->titulo }}</p>
                    </div>

                    <div class="event-card__text">
                        <p class="event-card__rating">
                            ⭐ {{ number_format($evento->valoracion, 1) }}
                        </p>
                        @if($evento->fecha)
                            <p style="margin-top:6px;font-size:12px;color:var(--text-dim);">
                                {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>

                    <button onclick="window.location.href='{{ route('evento.show', base64_encode($evento->idEve)) }}'"
                            class="event-card__button">
                        Reservar
                    </button>
                </div>
            @endforeach

            {{-- Pagination --}}
            @if($eventos->hasPages())
                <div class="pagination">
                    @if($eventos->onFirstPage())
                        <button class="pagination__button" disabled>← Anterior</button>
                    @else
                        <a href="{{ $eventos->previousPageUrl() }}" class="pagination__button">← Anterior</a>
                    @endif

                    @foreach(range(1, $eventos->lastPage()) as $page)
                        <span class="pagination__number {{ $page == $eventos->currentPage() ? 'pagination__number--active' : '' }}"
                              onclick="window.location.href='{{ $eventos->url($page) }}'">
                            {{ $page }}
                        </span>
                    @endforeach

                    @if($eventos->hasMorePages())
                        <a href="{{ $eventos->nextPageUrl() }}" class="pagination__button">Siguiente →</a>
                    @else
                        <button class="pagination__button" disabled>Siguiente →</button>
                    @endif
                </div>
            @endif
        @endif
    </div>

</div>

@push('scripts')
<script>
function setFilter(inputId, value) {
    var el = document.getElementById(inputId);
    if (el) {
        el.value = (el.value === value) ? '' : value;
    }
    document.getElementById('filtros-form').submit();
}
</script>
@endpush

@endsection
