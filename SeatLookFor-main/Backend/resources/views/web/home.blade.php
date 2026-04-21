@extends('web.layouts.app')

@section('title', 'SeatLookFor')

@section('content')

<div class="landing-page">

    {{-- Animated spotlight beams --}}
    <div class="landing-page__spotlight-container">
        <div class="landing-page__spotlight landing-page__spotlight--left"   style="--angle: 50deg;"></div>
        <div class="landing-page__spotlight landing-page__spotlight--right"  style="--angle: -50deg;"></div>
        <div class="landing-page__spotlight landing-page__spotlight--semi-left"  style="--angle: 104deg;"></div>
        <div class="landing-page__spotlight landing-page__spotlight--semi-right" style="--angle: -104deg;"></div>
    </div>

    {{-- Hero animated title (letters injected by JS) --}}
    <h1 class="landing-page__title" id="landing-title"></h1>

    {{-- Subtitle --}}
    <p style="text-align:center;color:var(--text-muted);font-size:1.05rem;position:relative;z-index:2;padding:0 20px 40px;max-width:560px;margin:0 auto;line-height:1.7;">
        Reserva tu asiento en teatros, salas y eventos locales — y descubre la vista desde cada butaca.
    </p>

    <div class="ante-container">
        <div class="landing-page__recommendations">
            <h3>Nuestras Recomendaciones</h3>
        </div>

        <div class="landing-page__container">
            <div class="landing-page__cards-container">
                @foreach($recientes as $evento)
                <div class="landing-page__card-wrapper">
                    <div class="landing-page__card">
                        {{-- Background image --}}
                        @if($evento->portada)
                            <img class="landing-page__card-image"
                                 src="{{ $evento->portada }}"
                                 alt="{{ $evento->titulo }}">
                        @else
                            <div class="landing-page__card-image"
                                 style="background: linear-gradient(135deg, #4c1d95, #1e1b4b);"></div>
                        @endif

                        {{-- Overlay details --}}
                        <div class="landing-page__card-details">
                            <h3 class="landing-page__card-title">{{ $evento->titulo }}</h3>
                            <p class="landing-page__card-description">
                                {{ Str::words($evento->descripcion, 10) }}
                            </p>
                        </div>
                    </div>

                    {{-- Amber CTA corner button (clip-path preserved) --}}
                    <a href="{{ route('evento.show', $evento->idEve) }}" class="landing-page__cta">⬊</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bottom CTA strip --}}
    <div style="text-align:center;position:relative;z-index:2;padding:8px 0 0;">
        <a href="{{ route('eventos.index') }}"
           style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;font-family:'Poppins',sans-serif;font-weight:700;font-size:15px;border-radius:10px;transition:transform 0.2s,box-shadow 0.2s;"
           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(139,92,246,0.4)'"
           onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">
            Ver todos los eventos
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</div>

@push('scripts')
<script>
(function () {
    var text = 'seatlookfor';
    var container = document.getElementById('landing-title');
    if (!container) return;
    text.split('').forEach(function (letter, i) {
        var span = document.createElement('span');
        span.className = 'landing-page__title-letter';
        span.textContent = letter === ' ' ? '\u00a0' : letter;
        span.style.setProperty('--i', i);
        span.style.animationDelay = (i * 0.07) + 's';
        container.appendChild(span);
    });
})();
</script>
@endpush

@endsection
