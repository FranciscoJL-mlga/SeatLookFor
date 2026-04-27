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

                {{-- ── Seats ── --}}
                <div class="detalles-asientos">
                    <h3>Asientos Seleccionados</h3>

                    @if($asientos->isNotEmpty())
                        <ul>
                            @foreach($asientos as $asiento)
                            <li>
                                <div class="asiento-info">
                                    <span>
                                        <strong style="color:var(--text);">Zona</strong>
                                        {{ $asiento['zona'] }}
                                    </span>
                                    <span>Fila {{ $asiento['ejeX'] }}</span>
                                    <span>Asiento {{ $asiento['ejeY'] }}</span>
                                    <span class="precio-asiento">
                                        {{ number_format($asiento['precio'], 2) }} €
                                    </span>
                                </div>
                            </li>
                            @endforeach
                        </ul>

                        <div class="total">
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
