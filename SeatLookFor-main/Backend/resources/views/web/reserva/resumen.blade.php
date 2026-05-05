@extends('web.layouts.app')

@section('title', 'Resumen de Reserva - SeatLookFor')

@push('scripts')
<script>
(function () {
    const expiresAt  = {{ $expiresAt->timestamp }} * 1000;
    const liberarUrl = "{{ route('reserva.liberar') }}";
    const eventoUrl  = "{{ route('evento.show', $evento->codigo) }}";
    const csrfToken  = "{{ csrf_token() }}";

    const timerEl = document.getElementById('bloqueo-timer');
    const modalEl = document.getElementById('bloqueo-modal');

    function liberar() {
        navigator.sendBeacon(liberarUrl, new URLSearchParams({ _token: csrfToken }));
    }

    function tick() {
        const remaining = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
        const m = String(Math.floor(remaining / 60)).padStart(2, '0');
        const s = String(remaining % 60).padStart(2, '0');
        if (timerEl) timerEl.textContent = m + ':' + s;

        if (remaining <= 0) {
            if (modalEl) modalEl.style.display = 'flex';
            liberar();
            setTimeout(() => { window.location.href = eventoUrl; }, 4000);
        } else {
            setTimeout(tick, 1000);
        }
    }

    window.addEventListener('pagehide', liberar);
    document.addEventListener('DOMContentLoaded', tick);
})();

(function () {
    var wrap  = document.getElementById('resumap-wrap');
    var inner = document.getElementById('resumap-inner');
    function fit() {
        if (!wrap || !inner) return;
        inner.style.transform = 'scale(1)';
        wrap.style.height = '';
        var naturalW = inner.offsetWidth;
        var naturalH = inner.offsetHeight;
        if (!naturalW) return;
        var scale = Math.min(1, wrap.offsetWidth / naturalW);
        inner.style.transform = 'scale(' + scale + ')';
        wrap.style.height = Math.ceil(naturalH * scale) + 'px';
    }
    document.addEventListener('DOMContentLoaded', fit);
    window.addEventListener('resize', fit);
})();
</script>
@endpush

@section('content')

<div class="vista">
    <div class="contenido" style="justify-content:center;">
        <div class="resumen-contenido">
            <div class="resumen-reserva">

                {{-- ── Timer ── --}}
                <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:20px;padding:12px 20px;background:rgba(249,115,22,0.1);border:1px solid rgba(249,115,22,0.4);border-radius:10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span style="font-size:13px;color:#f97316;font-weight:600;">
                        Tus asientos están reservados durante
                        <span id="bloqueo-timer" style="font-size:17px;font-family:monospace;letter-spacing:1px;">05:00</span>
                    </span>
                </div>

                {{-- ── Header ── --}}
                <h2>Resumen de tu Reserva</h2>

                {{-- ── Breadcrumb / back link ── --}}
                <div style="text-align:center;margin-bottom:28px;">
                    <a href="{{ route('evento.show', $evento->codigo) }}"
                       style="font-size:13px;color:var(--text-dim);display:inline-flex;align-items:center;gap:6px;transition:color 0.2s;"
                       onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-dim)'">
                        ← Volver al evento
                    </a>
                </div>

                {{-- ── Event details ── --}}
                <div class="detalles-evento">
                    <h3>Detalles del Evento</h3>
                    <div class="info-evento">
                        <h4>{{ $evento->titulo }}</h4>
                        <p>
                            <strong style="color:var(--text);">Fecha:</strong>
                            {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                        </p>
                        <p>
                            <strong style="color:var(--text);">Hora:</strong>
                            {{ \Carbon\Carbon::parse($evento->fecha)->format('H:i') }}
                        </p>
                        @if($evento->categoria)
                        <p>
                            <strong style="color:var(--text);">Categoría:</strong>
                            {{ ucfirst($evento->categoria) }}
                        </p>
                        @endif
                    </div>
                </div>

                {{-- ── Establishment ── --}}
                @if($evento->establecimiento)
                <div class="detalles-establecimiento">
                    <h3>Establecimiento</h3>
                    <div class="info-establecimiento">
                        @if($evento->establecimiento->imagen)
                            <img src="{{ $evento->establecimiento->imagen }}"
                                 alt="Imagen del establecimiento">
                        @endif
                        <h4>{{ $evento->establecimiento->nombre }}</h4>
                        <p>
                            <span style="margin-right:4px;">📍</span>
                            {{ $evento->establecimiento->ubicacion }}
                        </p>
                    </div>
                </div>
                @endif

                {{-- ── Seat map ── --}}
                <div class="detalles-asientos">
                    <h3>Asientos Seleccionados</h3>

                    @if($todosAsientos->isNotEmpty())
                    @php
                        $zonePalette = [
                            '#3b82f6','#10b981','#8b5cf6','#06b6d4',
                            '#f43f5e','#84cc16','#d946ef','#f97316',
                            '#14b8a6','#6366f1','#ec4899','#eab308',
                            '#22c55e','#ef4444','#fb923c','#38bdf8',
                            '#a3e635','#2dd4bf','#c026d3','#d97706',
                            '#1d4ed8','#15803d','#7c3aed','#be123c',
                            '#0891b2','#65a30d','#9333ea','#0284c7',
                            '#f472b6','#4ade80','#fb7185','#34d399',
                        ];
                        $uniqueZones = collect($todosAsientos)->pluck('zona')->unique()->values();
                        $zoneColors  = [];
                        foreach ($uniqueZones as $i => $z) {
                            $zoneColors[$z] = $zonePalette[$i % count($zonePalette)];
                        }
                        $allEjeX  = collect($todosAsientos)->pluck('ejeX');
                        $allEjeY  = collect($todosAsientos)->pluck('ejeY');
                        $minX     = $allEjeX->min();
                        $minY     = $allEjeY->min();
                        $maxX     = $allEjeX->max();
                        $maxY     = $allEjeY->max();
                        $mapW     = ($maxX - $minX + 2) * 50 + 20;
                        $mapH     = ($maxY - $minY + 2) * 50 + 44;
                    @endphp

                    {{-- Leyenda --}}
                    <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:14px;align-items:center;">
                        @foreach($zoneColors as $zona => $color)
                        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted);">
                            <span style="width:12px;height:12px;border-radius:3px;background:{{ $color }};display:inline-block;"></span>
                            Zona {{ $zona }}
                        </div>
                        @endforeach
                        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted);">
                            <span style="width:12px;height:12px;border-radius:3px;background:#f59e0b;display:inline-block;box-shadow:0 0 6px rgba(245,158,11,0.8);"></span>
                            Seleccionado
                        </div>
                    </div>

                    {{-- Mapa --}}
                    <div id="resumap-wrap" style="overflow:hidden;width:100%;">
                        <div id="resumap-inner" style="position:relative;width:{{ $mapW }}px;height:{{ $mapH }}px;background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:12px;transform-origin:top left;">
                            <div style="position:absolute;top:6px;left:50%;transform:translateX(-50%);background:#1e293b;color:#94a3b8;font-size:10px;font-weight:700;letter-spacing:2px;padding:3px 20px;border-radius:4px;white-space:nowrap;">ESCENARIO</div>
                            @foreach($todosAsientos as $asiento)
                            @php
                                $color = $zoneColors[$asiento['zona']] ?? '#64748b';
                                $left  = ($asiento['ejeX'] - $minX) * 50 + 10;
                                $top   = ($asiento['ejeY'] - $minY) * 50 + 30;
                            @endphp
                            <div title="Zona {{ $asiento['zona'] }} · Fila {{ $asiento['ejeY'] }} · Col {{ $asiento['ejeX'] }} · {{ number_format($asiento['precio'],2) }}€"
                                 style="position:absolute;left:{{ $left }}px;top:{{ $top }}px;width:44px;height:48px;
                                        {{ $asiento['seleccionado'] ? 'filter:drop-shadow(0 0 8px rgba(245,158,11,0.9));z-index:2;' : 'opacity:0.55;' }}">
                                <svg viewBox="0 0 44 48" width="44" height="48" xmlns="http://www.w3.org/2000/svg">
                                    @if($asiento['seleccionado'])
                                        <rect x="5" y="0" width="34" height="29" rx="7" fill="#f59e0b"/>
                                        <rect x="5" y="0" width="34" height="10" rx="7" fill="rgba(255,255,255,0.25)"/>
                                        <rect x="0" y="32" width="44" height="14" rx="5" fill="#f59e0b"/>
                                    @else
                                        <rect x="5" y="0" width="34" height="29" rx="7" fill="{{ $color }}"/>
                                        <rect x="5" y="0" width="34" height="10" rx="7" fill="rgba(255,255,255,0.15)"/>
                                        <rect x="0" y="32" width="44" height="14" rx="5" fill="{{ $color }}"/>
                                    @endif
                                </svg>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="total" style="margin-top:20px;">
                        <h4>Total a pagar: {{ number_format($total, 2) }} €</h4>
                    </div>
                    @else
                        <p style="color:var(--text-dim);font-style:italic;padding:8px 0;">
                            No hay asientos seleccionados.
                        </p>
                    @endif
                </div>

                {{-- ── Actions ── --}}
                <div class="acciones">
                    <a href="{{ route('evento.show', $evento->codigo) }}" class="btn-secundario">
                        ← Modificar Reserva
                    </a>

                    <form method="POST" action="{{ route('reserva.crear') }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="idEve"        value="{{ $evento->idEve }}">
                        <input type="hidden" name="totalPrecio"  value="{{ $total }}">
                        @foreach($asientos as $asiento)
                            <input type="hidden" name="idAsientos[]" value="{{ $asiento['idAsi'] }}">
                        @endforeach
                        <button type="submit"
                                class="btn-primario"
                                {{ $asientos->isEmpty() ? 'disabled' : '' }}>
                            Confirmar y Pagar →
                        </button>
                    </form>
                </div>

            </div>{{-- /.resumen-reserva --}}
        </div>{{-- /.resumen-contenido --}}
    </div>{{-- /.contenido --}}
</div>{{-- /.vista --}}

{{-- ── Modal tiempo expirado ── --}}
<div id="bloqueo-modal"
     style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.8);backdrop-filter:blur(6px);">
    <div style="background:#0f0f1a;border:1px solid #f97316;border-radius:16px;padding:36px 40px;text-align:center;max-width:380px;width:90%;box-shadow:0 0 40px rgba(249,115,22,0.3);">
        <div style="font-size:48px;margin-bottom:12px;">⏰</div>
        <h3 style="font-size:1.3rem;font-weight:700;color:#f97316;margin-bottom:10px;">¡Tiempo agotado!</h3>
        <p style="color:#9ca3af;font-size:.9rem;line-height:1.6;margin-bottom:20px;">
            El tiempo para completar tu compra ha expirado. Serás redirigido a la selección de asientos.
        </p>
        <div style="width:100%;height:4px;background:#1f2937;border-radius:2px;overflow:hidden;">
            <div style="height:100%;background:#f97316;border-radius:2px;animation:shrink 4s linear forwards;"></div>
        </div>
    </div>
</div>
<style>
@keyframes shrink { from { width:100%; } to { width:0%; } }
</style>

@endsection
