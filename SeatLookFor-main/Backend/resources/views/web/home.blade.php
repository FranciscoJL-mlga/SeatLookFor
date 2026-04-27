@extends('web.layouts.app')

@section('title', 'SeatLookFor')

@push('styles')
<style>
.landing-page { position: relative; overflow: hidden; }

/* ── Theater backdrop ── */
.theater-backdrop {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 0;
}

/* ── Spotlight canvas ── */
.spotlight-canvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

/* ── Theater frame ── */
.theater-frame {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 100%;
    min-height: 100vh;
    pointer-events: none;
    z-index: 5;
    overflow: visible;
}

/* ── Curtain panels ── */
.curtain-panel {
    position: absolute;
    top: 0;
    width: clamp(170px, 21vw, 300px);
    height: 100%;
    min-height: 100vh;
}
.curtain-panel--left  { left: 0; }
.curtain-panel--right { right: 0; transform: scaleX(-1); }

.curtain-panel__body {
    position: relative;
    width: 100%;
    height: 100%;
    background:
        repeating-linear-gradient(
            to right,
            #0d0003  0px,
            #420010  5px,
            #820020  11px,
            #9e0028  17px,
            #8a0022  23px,
            #5a0016  29px,
            #180005  35px,
            #5c0017  41px,
            #8e0024  48px,
            #780020  54px,
            #380010  60px
        );
    clip-path: polygon(
        0 0, 100% 0,
        100%  5%, 97% 10%, 100% 16%, 97% 21%, 99% 27%,
        97% 33%, 100% 39%, 97% 44%, 99% 50%, 97% 56%,
        100% 62%, 97% 67%, 99% 73%, 96% 79%, 98% 84%,
        93% 89%, 85% 93%, 75% 89%, 63% 95%,
        52% 90%, 40% 96%, 28% 91%, 17% 97%,
        8% 93%, 0 96%
    );
}
.curtain-panel__body::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        linear-gradient(to left,
            rgba(0,0,0,0.55) 0%,
            rgba(0,0,0,0.18) 35%,
            rgba(0,0,0,0)    55%,
            rgba(0,0,0,0.22) 100%),
        linear-gradient(180deg,
            rgba(255,200,140,0.13) 0%,
            rgba(255,180,100,0.04) 28%,
            rgba(0,0,0,0.08)       62%,
            rgba(0,0,0,0.50)       100%);
    clip-path: inherit;
}
.curtain-panel__body::after {
    content: '';
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(
        to bottom,
        transparent                  0px,
        transparent                  22px,
        rgba(255,255,255,0.018)      24px,
        transparent                  26px
    );
    clip-path: inherit;
}
.curtain-panel__trim {
    position: absolute;
    top: 0; right: 0;
    width: 3px;
    height: 84%;
    background: linear-gradient(180deg,
        #e8c030 0%, #c9a227 30%,
        #a8821a 60%, transparent 100%);
    opacity: 0.75;
    filter: blur(0.5px);
}
.curtain-panel__tassel {
    position: absolute;
    right: -5px;
    top: 83%;
    width: 10px; height: 10px;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, #f0d050 0%, #c9a227 45%, #7a6010 100%);
    box-shadow: 0 2px 6px rgba(0,0,0,0.6);
}

/* ── Valance ── */
.theater-valance {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 72px;
    z-index: 6;
    pointer-events: none;
}

/* ── Responsive: hide theater & spotlights on tablet/mobile ── */
@media (max-width: 960px) {
    .theater-frame    { display: none; }
    .theater-backdrop { display: none; }
    .spotlight-canvas { display: none; }
}

/* Content above decorative layers */
.landing-page > *:not(.spotlight-canvas):not(.theater-frame):not(.theater-backdrop) {
    position: relative;
    z-index: 2;
}
</style>
@endpush

@section('content')

<div class="landing-page" id="landing-page">

    {{-- Theater backdrop --}}
    <div class="theater-backdrop" aria-hidden="true">
        <svg viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice"
             xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;display:block;">
            <defs>
                <radialGradient id="tb-glow" cx="50%" cy="38%" r="52%">
                    <stop offset="0%"   stop-color="#3d1206" stop-opacity="0.85"/>
                    <stop offset="40%"  stop-color="#1a0608" stop-opacity="0.6"/>
                    <stop offset="100%" stop-color="#000000" stop-opacity="0"/>
                </radialGradient>
                <radialGradient id="tb-top" cx="50%" cy="0%" r="70%">
                    <stop offset="0%"   stop-color="#4a1208" stop-opacity="0.55"/>
                    <stop offset="100%" stop-color="#000000" stop-opacity="0"/>
                </radialGradient>
                <linearGradient id="tb-floor" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%"   stop-color="#1c0c04"/>
                    <stop offset="100%" stop-color="#080402"/>
                </linearGradient>
                <linearGradient id="tb-ls" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%"   stop-color="#000" stop-opacity="0.92"/>
                    <stop offset="100%" stop-color="#000" stop-opacity="0"/>
                </linearGradient>
                <linearGradient id="tb-rs" x1="100%" y1="0%" x2="0%" y2="0%">
                    <stop offset="0%"   stop-color="#000" stop-opacity="0.92"/>
                    <stop offset="100%" stop-color="#000" stop-opacity="0"/>
                </linearGradient>
                <linearGradient id="tb-col" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%"   stop-color="#1a0808"/>
                    <stop offset="100%" stop-color="#0d0404"/>
                </linearGradient>
                <filter id="tb-blur">
                    <feGaussianBlur stdDeviation="3"/>
                </filter>
            </defs>

            <!-- Base deep background -->
            <rect width="1440" height="900" fill="#040103"/>

            <!-- Warm center stage glow -->
            <ellipse cx="720" cy="340" rx="620" ry="440" fill="url(#tb-glow)"/>

            <!-- Top arch glow -->
            <ellipse cx="720" cy="0" rx="800" ry="260" fill="url(#tb-top)"/>

            <!-- Back wall — center arch opening -->
            <path d="M 340,900 L 340,320 Q 340,100 720,100 Q 1100,100 1100,320 L 1100,900 Z"
                  fill="#0d0407" opacity="0.5"/>

            <!-- Proscenium arch molding -->
            <path d="M 310,900 L 310,330 Q 310,72 720,72 Q 1130,72 1130,330 L 1130,900"
                  fill="none" stroke="#2a0a10" stroke-width="28" opacity="0.8"/>
            <path d="M 310,900 L 310,330 Q 310,72 720,72 Q 1130,72 1130,330 L 1130,900"
                  fill="none" stroke="#1a0608" stroke-width="26" opacity="0.9"/>
            <!-- Gold arch edge -->
            <path d="M 325,900 L 325,332 Q 325,86 720,86 Q 1115,86 1115,332 L 1115,900"
                  fill="none" stroke="#c9a227" stroke-width="1.5" opacity="0.25"/>

            <!-- Decorative rosettes on arch top -->
            <circle cx="720" cy="82" r="12" fill="#1a0608" stroke="#c9a227" stroke-width="1" opacity="0.5"/>
            <circle cx="720" cy="82" r="6"  fill="#c9a227" opacity="0.35"/>
            <circle cx="580" cy="118" r="7" fill="#1a0608" stroke="#c9a227" stroke-width="1" opacity="0.4"/>
            <circle cx="860" cy="118" r="7" fill="#1a0608" stroke="#c9a227" stroke-width="1" opacity="0.4"/>

            <!-- Back wall pilasters (vertical decorative columns) -->
            <rect x="450" y="100" width="16" height="580" fill="url(#tb-col)" opacity="0.7"/>
            <rect x="450" y="100" width="16" height="580" fill="url(#tb-col)" opacity="0.7"/>
            <rect x="460" y="100" width="4"  height="580" fill="#c9a227" opacity="0.08"/>
            <rect x="974" y="100" width="16" height="580" fill="url(#tb-col)" opacity="0.7"/>
            <rect x="984" y="100" width="4"  height="580" fill="#c9a227" opacity="0.08"/>
            <rect x="620" y="100" width="10" height="580" fill="url(#tb-col)" opacity="0.45"/>
            <rect x="810" y="100" width="10" height="580" fill="url(#tb-col)" opacity="0.45"/>

            <!-- Back wall center ornament -->
            <ellipse cx="720" cy="340" rx="90" ry="110" fill="none" stroke="#2a0a10" stroke-width="2" opacity="0.5"/>
            <ellipse cx="720" cy="340" rx="70" ry="88"  fill="none" stroke="#c9a227" stroke-width="1" opacity="0.18"/>

            <!-- Stage floor (perspective, vanishing point at center) -->
            <path d="M 280,660 L 1160,660 L 1440,900 L 0,900 Z" fill="url(#tb-floor)"/>
            <!-- Floor boards (perspective lines) -->
            <line x1="720" y1="660" x2="720"  y2="900" stroke="#250f04" stroke-width="2" opacity="0.6"/>
            <line x1="720" y1="660" x2="520"  y2="900" stroke="#200d04" stroke-width="1.5" opacity="0.45"/>
            <line x1="720" y1="660" x2="920"  y2="900" stroke="#200d04" stroke-width="1.5" opacity="0.45"/>
            <line x1="720" y1="660" x2="330"  y2="900" stroke="#1a0b03" stroke-width="1" opacity="0.35"/>
            <line x1="720" y1="660" x2="1110" y2="900" stroke="#1a0b03" stroke-width="1" opacity="0.35"/>
            <line x1="720" y1="660" x2="150"  y2="900" stroke="#180a03" stroke-width="0.8" opacity="0.25"/>
            <line x1="720" y1="660" x2="1290" y2="900" stroke="#180a03" stroke-width="0.8" opacity="0.25"/>
            <line x1="720" y1="660" x2="0"    y2="870" stroke="#160903" stroke-width="0.6" opacity="0.2"/>
            <line x1="720" y1="660" x2="1440" y2="870" stroke="#160903" stroke-width="0.6" opacity="0.2"/>
            <!-- Floor horizontal boards -->
            <line x1="0" y1="710" x2="1440" y2="710" stroke="#1e0e05" stroke-width="1.2" opacity="0.4"/>
            <line x1="0" y1="760" x2="1440" y2="760" stroke="#1a0c04" stroke-width="1" opacity="0.35"/>
            <line x1="0" y1="810" x2="1440" y2="810" stroke="#180b03" stroke-width="1" opacity="0.3"/>
            <line x1="0" y1="855" x2="1440" y2="855" stroke="#150a03" stroke-width="0.8" opacity="0.25"/>

            <!-- Stage front edge / apron -->
            <rect x="0" y="656" width="1440" height="6" fill="#c9a227" opacity="0.12"/>

            <!-- Left balcony box (1st tier) -->
            <rect x="0" y="180" width="280" height="22" fill="#0e0407" opacity="0.95"/>
            <path d="M 0,202 Q 80,215 180,202 L 280,202 L 280,180 L 0,180 Z" fill="#080204" opacity="0.8"/>
            <!-- Left balcony railing balusters -->
            <rect x="0" y="180" width="260" height="3" fill="#c9a227" opacity="0.18"/>
            @for ($bx = 12; $bx <= 260; $bx += 20)
            <line x1="{{ $bx }}" y1="183" x2="{{ $bx }}" y2="220"
                  stroke="#1e0a0e" stroke-width="1.5" opacity="0.55"/>
            @endfor

            <!-- Left balcony box (2nd tier) -->
            <rect x="0" y="370" width="250" height="18" fill="#0e0407" opacity="0.85"/>
            <rect x="0" y="370" width="230" height="2" fill="#c9a227" opacity="0.14"/>
            @for ($bx = 12; $bx <= 220; $bx += 20)
            <line x1="{{ $bx }}" y1="372" x2="{{ $bx }}" y2="405"
                  stroke="#1e0a0e" stroke-width="1.2" opacity="0.45"/>
            @endfor

            <!-- Right balcony box (1st tier) -->
            <rect x="1160" y="180" width="280" height="22" fill="#0e0407" opacity="0.95"/>
            <path d="M 1440,202 Q 1360,215 1260,202 L 1160,202 L 1160,180 L 1440,180 Z" fill="#080204" opacity="0.8"/>
            <rect x="1180" y="180" width="260" height="3" fill="#c9a227" opacity="0.18"/>
            @for ($bx = 1188; $bx <= 1428; $bx += 20)
            <line x1="{{ $bx }}" y1="183" x2="{{ $bx }}" y2="220"
                  stroke="#1e0a0e" stroke-width="1.5" opacity="0.55"/>
            @endfor

            <!-- Right balcony box (2nd tier) -->
            <rect x="1190" y="370" width="250" height="18" fill="#0e0407" opacity="0.85"/>
            <rect x="1210" y="370" width="230" height="2" fill="#c9a227" opacity="0.14"/>
            @for ($bx = 1218; $bx <= 1428; $bx += 20)
            <line x1="{{ $bx }}" y1="372" x2="{{ $bx }}" y2="405"
                  stroke="#1e0a0e" stroke-width="1.2" opacity="0.45"/>
            @endfor

            <!-- Top ceiling molding -->
            <rect x="0" y="0" width="1440" height="68" fill="#050102"/>
            <rect x="0" y="65" width="1440" height="3" fill="#c9a227" opacity="0.2"/>

            <!-- Side shadows (blend into curtains) -->
            <rect x="0"    y="0" width="320" height="900" fill="url(#tb-ls)"/>
            <rect x="1120" y="0" width="320" height="900" fill="url(#tb-rs)"/>

            <!-- Bottom vignette -->
            <rect x="0" y="750" width="1440" height="150"
                  fill="url(#tb-floor)" opacity="0.4"/>
        </svg>
    </div>

    <canvas id="spotlight-canvas" class="spotlight-canvas"></canvas>

    {{-- Theater frame --}}
    <div class="theater-frame" aria-hidden="true">

        <div class="theater-valance">
            <svg viewBox="0 0 1440 72" preserveAspectRatio="none"
                 xmlns="http://www.w3.org/2000/svg"
                 style="width:100%;height:100%;display:block;">
                <defs>
                    <linearGradient id="vg-h" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%"   stop-color="#2e000a"/>
                        <stop offset="10%"  stop-color="#7a001e"/>
                        <stop offset="22%"  stop-color="#520014"/>
                        <stop offset="35%"  stop-color="#920024"/>
                        <stop offset="50%"  stop-color="#6c001a"/>
                        <stop offset="65%"  stop-color="#920024"/>
                        <stop offset="78%"  stop-color="#520014"/>
                        <stop offset="90%"  stop-color="#7a001e"/>
                        <stop offset="100%" stop-color="#2e000a"/>
                    </linearGradient>
                    <linearGradient id="vg-v" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%"  stop-color="rgba(0,0,0,0.45)"/>
                        <stop offset="45%" stop-color="rgba(0,0,0,0)"/>
                    </linearGradient>
                </defs>
                <path d="M0,0 L1440,0 L1440,38
                    Q1368,72 1296,38 Q1224,6 1152,40
                    Q1080,72 1008,38 Q936,6 864,40
                    Q792,72 720,38 Q648,6 576,40
                    Q504,72 432,38 Q360,6 288,40
                    Q216,72 144,38 Q72,6 0,40 Z"
                    fill="url(#vg-h)"/>
                <path d="M0,0 L1440,0 L1440,38
                    Q1368,72 1296,38 Q1224,6 1152,40
                    Q1080,72 1008,38 Q936,6 864,40
                    Q792,72 720,38 Q648,6 576,40
                    Q504,72 432,38 Q360,6 288,40
                    Q216,72 144,38 Q72,6 0,40 Z"
                    fill="url(#vg-v)"/>
                <path d="M0,40 Q72,6 144,38 Q216,72 288,40
                    Q360,6 432,38 Q504,72 576,40
                    Q648,6 720,38 Q792,72 864,40
                    Q936,6 1008,38 Q1080,72 1152,40
                    Q1224,6 1296,38 Q1368,72 1440,40"
                    stroke="#c9a227" stroke-width="2.5" fill="none" opacity="0.75"/>
                @foreach([72, 216, 360, 504, 648, 792, 936, 1080, 1224, 1368] as $tx)
                    <line x1="{{ $tx }}" y1="68" x2="{{ $tx }}" y2="72"
                          stroke="#c9a227" stroke-width="1.5" opacity="0.7"/>
                    <polygon
                        points="{{ $tx }},72 {{ $tx - 4 }},64 {{ $tx }},57 {{ $tx + 4 }},64"
                        fill="#d4aa37" opacity="0.85"/>
                    <circle cx="{{ $tx }}" cy="57" r="2.5" fill="#e0c040" opacity="0.9"/>
                @endforeach
            </svg>
        </div>

        <div class="curtain-panel curtain-panel--left">
            <div class="curtain-panel__body"></div>
            <div class="curtain-panel__trim"></div>
            <div class="curtain-panel__tassel"></div>
        </div>
        <div class="curtain-panel curtain-panel--right">
            <div class="curtain-panel__body"></div>
            <div class="curtain-panel__trim"></div>
            <div class="curtain-panel__tassel"></div>
        </div>

    </div>

    <h1 class="landing-page__title" id="landing-title"></h1>

    <p style="text-align:center;color:var(--text-muted);font-size:1.05rem;position:relative;z-index:2;
              padding:0 20px 40px;max-width:560px;margin:0 auto;line-height:1.7;">
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
                        @if($evento->portada)
                            <img class="landing-page__card-image"
                                 src="{{ $evento->portada }}"
                                 alt="{{ $evento->titulo }}">
                        @else
                            <div class="landing-page__card-image"
                                 style="background: linear-gradient(135deg, #3a000e, #800020);"></div>
                        @endif
                        <div class="landing-page__card-details">
                            <h3 class="landing-page__card-title">{{ $evento->titulo }}</h3>
                            <p class="landing-page__card-description">
                                {{ Str::words($evento->descripcion, 10) }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('evento.show', $evento->codigo) }}" class="landing-page__cta">⬊</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div style="text-align:center;position:relative;z-index:2;padding:8px 0 0;">
        <a href="{{ route('eventos.index') }}"
           style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;
                  background:var(--accent);color:#f0e2c8;
                  font-family:'Poppins',sans-serif;font-weight:700;font-size:15px;
                  border-radius:8px;transition:background 0.2s;"
           onmouseover="this.style.background='var(--accent-dark)'"
           onmouseout="this.style.background='var(--accent)'">
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
/* ── Letter animation ── */
(function () {
    var text = 'seatlookfor';
    var container = document.getElementById('landing-title');
    if (!container) return;
    text.split('').forEach(function (letter, i) {
        var span = document.createElement('span');
        span.className = 'landing-page__title-letter';
        span.textContent = letter;
        span.style.setProperty('--i', i);
        container.appendChild(span);
    });
})();

/* ── Spotlight animation ── */
(function () {
    var canvas  = document.getElementById('spotlight-canvas');
    if (!canvas) return;

    var ctx     = canvas.getContext('2d');
    var landing = document.getElementById('landing-page');
    var DEG     = Math.PI / 180;
    var lightsOn = true;
    var frame    = 0;

    /* Speed toward a card vs returning to park position */
    var EASE_TRACK  = 0.06;
    var EASE_RETURN = 0.022;

    /* Spotlight sources at the top, slightly inset from the sides.
       Default angle = pointing almost straight down (park position). */
    var DEFAULT_L = Math.PI / 2 + 0.18;   /* left:  slightly right of straight down */
    var DEFAULT_R = Math.PI / 2 - 0.18;   /* right: slightly left  of straight down */

    var lights = [
        { xFrac: 0.25, yFrac: 0.0, angle: DEFAULT_L, targetAngle: DEFAULT_L, wobbleOffset: 0 },
        { xFrac: 0.75, yFrac: 0.0, angle: DEFAULT_R, targetAngle: DEFAULT_R, wobbleOffset: Math.PI }
    ];

    var activeEase = EASE_RETURN;

    function lerpAngle(a, b, t) {
        var d = b - a;
        while (d >  Math.PI) d -= Math.PI * 2;
        while (d < -Math.PI) d += Math.PI * 2;
        return a + d * t;
    }

    function resize() {
        canvas.width  = landing.offsetWidth;
        canvas.height = landing.offsetHeight;
    }

    function aimAt(cx, cy) {
        activeEase = EASE_TRACK;
        lights.forEach(function (light) {
            var lx = light.xFrac * canvas.width;
            var ly = light.yFrac * canvas.height;
            light.targetAngle = Math.atan2(cy - ly, cx - lx);
        });
    }

    function park() {
        activeEase = EASE_RETURN;
        lights[0].targetAngle = DEFAULT_L;
        lights[1].targetAngle = DEFAULT_R;
    }

    /* Listen to each card */
    function bindCards() {
        var cards = landing.querySelectorAll('.landing-page__card');
        cards.forEach(function (card) {
            card.addEventListener('mouseenter', function () {
                var cr  = card.getBoundingClientRect();
                var cvr = canvas.getBoundingClientRect();
                aimAt(cr.left + cr.width  / 2 - cvr.left,
                      cr.top  + cr.height / 2 - cvr.top);
            });
            card.addEventListener('mouseleave', park);
        });
    }

    function drawLight(light) {
        var W = canvas.width, H = canvas.height;
        var lx   = light.xFrac * W;
        var ly   = light.yFrac * H;
        var len  = Math.sqrt(W * W + H * H) * 1.2;
        var ang  = light.angle;
        var half = 15 * DEG;

        /* Outer beam cone */
        var grad = ctx.createRadialGradient(lx, ly, 0, lx, ly, len);
        grad.addColorStop(0,    'rgba(255,248,200,0.40)');
        grad.addColorStop(0.08, 'rgba(255,240,160,0.24)');
        grad.addColorStop(0.35, 'rgba(255,240,160,0.09)');
        grad.addColorStop(0.70, 'rgba(255,240,160,0.03)');
        grad.addColorStop(1,    'rgba(255,240,160,0)');

        ctx.save();
        ctx.beginPath();
        ctx.moveTo(lx, ly);
        ctx.arc(lx, ly, len, ang - half, ang + half);
        ctx.closePath();
        ctx.fillStyle = grad;
        ctx.fill();
        ctx.restore();

        /* Bright centre strip */
        var hotGrad = ctx.createRadialGradient(lx, ly, 0, lx, ly, len * 0.6);
        hotGrad.addColorStop(0,   'rgba(255,255,230,0.25)');
        hotGrad.addColorStop(0.5, 'rgba(255,255,210,0.05)');
        hotGrad.addColorStop(1,   'rgba(255,255,210,0)');

        ctx.save();
        ctx.beginPath();
        ctx.moveTo(lx, ly);
        ctx.arc(lx, ly, len * 0.6, ang - 4 * DEG, ang + 4 * DEG);
        ctx.closePath();
        ctx.fillStyle = hotGrad;
        ctx.fill();
        ctx.restore();

        /* Source bulb glow */
        var bulb = ctx.createRadialGradient(lx, ly, 0, lx, ly, 16);
        bulb.addColorStop(0,   'rgba(255,255,210,0.9)');
        bulb.addColorStop(0.4, 'rgba(255,240,150,0.4)');
        bulb.addColorStop(1,   'rgba(255,240,150,0)');
        ctx.beginPath();
        ctx.arc(lx, ly, 16, 0, Math.PI * 2);
        ctx.fillStyle = bulb;
        ctx.fill();
    }

    function animate() {
        if (canvas.width !== landing.offsetWidth ||
            canvas.height !== landing.offsetHeight) resize();

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (lightsOn) {
            lights.forEach(function (light) {
                var sway = Math.sin(frame * 0.01 + light.wobbleOffset) * 0.005;
                light.angle = lerpAngle(light.angle, light.targetAngle, activeEase) + sway;
                drawLight(light);
            });
        }

        frame++;
        requestAnimationFrame(animate);
    }

    resize();
    bindCards();
    animate();
})();
</script>
@endpush

@endsection
