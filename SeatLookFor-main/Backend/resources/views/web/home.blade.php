@extends('web.layouts.app')

@section('title', 'SeatLookFor')

@push('styles')
<style>
.landing-page { position: relative; overflow: hidden; }

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
    height: 580px;
    pointer-events: none;
    z-index: 5;
    overflow: visible;
}

/* ── Curtain panels ── */
.curtain-panel {
    position: absolute;
    top: 0;
    width: clamp(130px, 16vw, 230px);
    height: 520px;
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
            #1e0008  0px,  #6a001a  6px,  #920026 14px,
            #780020 20px,  #500014 26px,  #800020 33px,
            #9c0026 41px,  #6e001b 48px,  #380010 54px
        );
    clip-path: polygon(
        0 0, 100% 0, 100% 72%,
        90% 78%, 80% 72%, 70% 80%, 59% 74%,
        48% 82%, 37% 75%, 26% 83%, 15% 77%,
        6% 84%, 0 78%
    );
}
.curtain-panel__body::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg,
        rgba(255,180,120,0.09) 0%,
        rgba(255,180,120,0.02) 35%,
        rgba(0,0,0,0.12) 70%,
        rgba(0,0,0,0.38) 100%);
    clip-path: inherit;
}
.curtain-panel__body::after {
    content: '';
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(
        to bottom,
        transparent 0px, transparent 18px,
        rgba(255,255,255,0.015) 20px, transparent 22px
    );
    clip-path: inherit;
}
.curtain-panel__trim {
    position: absolute;
    top: 0; right: 0;
    width: 2px;
    height: 72%;
    background: linear-gradient(180deg, #d4a820 0%, #a8821a 55%, transparent 100%);
    opacity: 0.65;
}
.curtain-panel__tassel {
    position: absolute;
    right: -3px;
    top: 70%;
    width: 8px; height: 8px;
    border-radius: 50%;
    background: radial-gradient(circle, #e0b830 0%, #a88020 70%);
}

/* ── Valance ── */
.theater-valance {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 72px;
    z-index: 6;
    pointer-events: none;
}

/* ── Light switch (desktop only) ── */
.lswitch {
    position: fixed;
    bottom: 32px;
    right: 28px;
    z-index: 300;
    width: 34px;
    height: 58px;
    background: #18100a;
    border: 1.5px solid #5a3810;
    border-radius: 6px;
    box-shadow: 0 3px 14px rgba(0,0,0,0.75), inset 0 1px 0 rgba(200,150,60,0.06);
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-around;
    padding: 5px 5px 6px;
    transition: border-color 0.3s;
}
.lswitch[aria-pressed="true"]  { border-color: rgba(201,162,39,0.55); }
.lswitch[aria-pressed="false"] { border-color: #5a3810; }

.lswitch__rocker {
    width: 100%;
    height: 44%;
    border-radius: 3px;
    background: #2e1c08;
    transition: background 0.3s, transform 0.25s;
    position: relative;
}
.lswitch__rocker::after {
    content: '';
    position: absolute;
    inset: 2px;
    border-radius: 2px;
    background: rgba(255,255,255,0.03);
}
.lswitch[aria-pressed="true"]  .lswitch__rocker { background: #c9a227; transform: translateY(2px); }
.lswitch[aria-pressed="false"] .lswitch__rocker { background: #2e1c08; transform: translateY(-2px); }

.lswitch__dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #3a2008;
    transition: background 0.3s;
}
.lswitch[aria-pressed="true"]  .lswitch__dot { background: #e0c040; }
.lswitch[aria-pressed="false"] .lswitch__dot { background: #3a2008; }

/* ── Responsive: hide theater & spotlights on tablet/mobile ── */
@media (max-width: 960px) {
    .theater-frame   { display: none; }
    .spotlight-canvas{ display: none; }
    .lswitch         { display: none; }
}

/* Content above canvas */
.landing-page > *:not(.spotlight-canvas):not(.theater-frame) {
    position: relative;
    z-index: 2;
}
</style>
@endpush

@section('content')

<div class="landing-page" id="landing-page">

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

    {{-- Light switch button --}}
    <button class="lswitch" id="lswitch" aria-pressed="true" title="Apagar/Encender focos">
        <span class="lswitch__rocker"></span>
        <span class="lswitch__dot"></span>
    </button>

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
                    <a href="{{ route('evento.show', base64_encode($evento->idEve)) }}" class="landing-page__cta">⬊</a>
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
    var lswitch = document.getElementById('lswitch');
    if (!canvas || !lswitch) return;

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

    lswitch.addEventListener('click', function () {
        lightsOn = !lightsOn;
        lswitch.setAttribute('aria-pressed', lightsOn ? 'true' : 'false');
        if (!lightsOn) park();
    });

    resize();
    bindCards();
    animate();
})();
</script>
@endpush

@endsection
