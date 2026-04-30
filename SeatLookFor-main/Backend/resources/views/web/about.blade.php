@extends('web.layouts.app')

@section('title', 'Sobre nosotros - SeatLookFor')

@section('content')

<div class="layout" style="overflow-y:auto;">
    <div class="about">

        {{-- ── Page heading ── --}}
        <div style="text-align:center;padding:16px 0 8px;position:relative;z-index:2;">
            <p style="font-family:'Poppins',sans-serif;font-size:12px;font-weight:600;
                      text-transform:uppercase;letter-spacing:4px;color:var(--accent);margin-bottom:10px;">
                El equipo
            </p>
            <h1 style="font-family:'Poppins',sans-serif;font-size:clamp(2rem,5vw,3rem);font-weight:700;
                       background:linear-gradient(135deg,var(--text) 0%,var(--primary) 100%);
                       -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
                       line-height:1.15;">
                Sobre SeatLookFor
            </h1>
        </div>

        {{-- ── Francisco López ── --}}
        <div class="about__card about__card--paco">
            <div class="about__header">
                <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="white">
                        <path d="M12 4a4 4 0 0 1 4 4 4 4 0 0 1-4 4 4 4 0 0 1-4-4 4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
                    </svg>
                </div>
                <h1 class="about__card-title">Francisco Jiménez López</h1>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:4px;">
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:rgba(139,92,246,0.12);border:1px solid rgba(139,92,246,0.25);border-radius:20px;font-size:12px;color:var(--primary);font-weight:600;">
                    Backend
                </span>
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:rgba(245,158,11,0.10);border:1px solid rgba(245,158,11,0.25);border-radius:20px;font-size:12px;color:var(--accent);font-weight:600;">
                    Laravel
                </span>
                <a href="https://www.linkedin.com/in/francisco-jimenez-lopez-1a1517217" target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(10,102,194,0.12);border:1px solid rgba(10,102,194,0.35);border-radius:20px;font-size:12px;color:#4a9fd4;font-weight:600;text-decoration:none;transition:background 0.2s;"
                   onmouseover="this.style.background='rgba(10,102,194,0.25)'" onmouseout="this.style.background='rgba(10,102,194,0.12)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                    LinkedIn
                </a>
            </div>
            <p class="about__card-text">Desarrollador Web con especialización en backend, recién graduado en DAW y con experiencia práctica en proyectos reales. Me apasiona diseñar la lógica que hay detrás de las aplicaciones: que todo funcione de forma eficiente, estructurada y escalable.</p>

            {{-- Stack tecnológico --}}
            <div style="margin:16px 0 20px;display:flex;flex-wrap:wrap;gap:8px;">
                @foreach(['PHP', 'Laravel', 'MySQL', 'PostgreSQL', 'JavaScript', 'HTML/CSS', 'Python', 'Angular', 'AWS', 'Tailwind CSS', 'Bootstrap', 'Figma'] as $tech)
                <span style="padding:4px 11px;border-radius:99px;font-size:11px;font-weight:600;
                             background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.12);
                             color:var(--text-dim);">{{ $tech }}</span>
                @endforeach
            </div>

            {{-- Puntos clave --}}
            <ul style="display:flex;flex-direction:column;gap:10px;padding:0;list-style:none;margin:0 0 8px;">
                <li style="display:flex;gap:10px;align-items:flex-start;font-size:13.5px;color:var(--text-dim);line-height:1.6;">
                    <span style="color:var(--primary);margin-top:2px;flex-shrink:0;">▸</span>
                    Desarrollo de APIs REST con Laravel y gestión de bases de datos relacionales (MySQL, PostgreSQL).
                </li>
                <li style="display:flex;gap:10px;align-items:flex-start;font-size:13.5px;color:var(--text-dim);line-height:1.6;">
                    <span style="color:var(--primary);margin-top:2px;flex-shrink:0;">▸</span>
                    Experiencia en despliegue en la nube con AWS y entornos de producción reales.
                </li>
                <li style="display:flex;gap:10px;align-items:flex-start;font-size:13.5px;color:var(--text-dim);line-height:1.6;">
                    <span style="color:var(--primary);margin-top:2px;flex-shrink:0;">▸</span>
                    Capacidad de adaptación y trabajo en equipo, reforzada por años de experiencia en el sector de la hostelería.
                </li>
                <li style="display:flex;gap:10px;align-items:flex-start;font-size:13.5px;color:var(--text-dim);line-height:1.6;">
                    <span style="color:var(--primary);margin-top:2px;flex-shrink:0;">▸</span>
                    Actualmente buscando una primera oportunidad profesional donde seguir creciendo en desarrollo de software.
                </li>
            </ul>
        </div>

        {{-- ── Antonio Jesus Heredias ── --}}
        <div class="about__card about__card--toni">
            <div class="about__header" style="justify-content:flex-end;">
                <h1 class="about__card-title">Antonio Jesus Heredias</h1>
                <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--primary));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="white">
                        <path d="M12 4a4 4 0 0 1 4 4 4 4 0 0 1-4 4 4 4 0 0 1-4-4 4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
                    </svg>
                </div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;align-items:center;margin-bottom:4px;">
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:rgba(16,185,129,0.10);border:1px solid rgba(16,185,129,0.25);border-radius:20px;font-size:12px;color:#10b981;font-weight:600;">
                    Frontend
                </span>
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;background:rgba(245,158,11,0.10);border:1px solid rgba(245,158,11,0.25);border-radius:20px;font-size:12px;color:var(--accent);font-weight:600;">
                    Diseño
                </span>
                <a href="https://www.linkedin.com/in/antoniojheredia/" target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(10,102,194,0.12);border:1px solid rgba(10,102,194,0.35);border-radius:20px;font-size:12px;color:#4a9fd4;font-weight:600;text-decoration:none;transition:background 0.2s;"
                   onmouseover="this.style.background='rgba(10,102,194,0.25)'" onmouseout="this.style.background='rgba(10,102,194,0.12)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                    LinkedIn
                </a>
            </div>
            <p class="about__card-text">Desarrollador Frontend con pasión por el diseño de interfaces, recién graduado en DAW. Me especializo en crear experiencias visuales atractivas, intuitivas y funcionales — donde el código y el diseño se encuentran.</p>

            {{-- Stack tecnológico --}}
            <div style="margin:16px 0 20px;display:flex;flex-wrap:wrap;gap:8px;">
                @foreach(['HTML/CSS', 'JavaScript', 'Tailwind CSS', 'Alpine.js', 'Bootstrap', 'PHP', 'Laravel', 'AWS', 'Diseño Responsive'] as $tech)
                <span style="padding:4px 11px;border-radius:99px;font-size:11px;font-weight:600;
                             background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.12);
                             color:var(--text-dim);">{{ $tech }}</span>
                @endforeach
            </div>

            {{-- Puntos clave --}}
            <ul style="display:flex;flex-direction:column;gap:10px;padding:0;list-style:none;margin:0 0 8px;">
                <li style="display:flex;gap:10px;align-items:flex-start;font-size:13.5px;color:var(--text-dim);line-height:1.6;">
                    <span style="color:#10b981;margin-top:2px;flex-shrink:0;">▸</span>
                    Maquetación de interfaces modernas y responsivas con HTML, CSS, Tailwind y JavaScript.
                </li>
                <li style="display:flex;gap:10px;align-items:flex-start;font-size:13.5px;color:var(--text-dim);line-height:1.6;">
                    <span style="color:#10b981;margin-top:2px;flex-shrink:0;">▸</span>
                    Enfoque en la experiencia de usuario: diseño limpio, accesible e intuitivo desde el primer clic.
                </li>
                <li style="display:flex;gap:10px;align-items:flex-start;font-size:13.5px;color:var(--text-dim);line-height:1.6;">
                    <span style="color:#10b981;margin-top:2px;flex-shrink:0;">▸</span>
                    Capacidad para traducir ideas y mockups en productos visuales reales, trabajando codo a codo con backend.
                </li>
                <li style="display:flex;gap:10px;align-items:flex-start;font-size:13.5px;color:var(--text-dim);line-height:1.6;">
                    <span style="color:#10b981;margin-top:2px;flex-shrink:0;">▸</span>
                    Actualmente buscando una oportunidad donde seguir creciendo en frontend y diseño de producto.
                </li>
            </ul>
        </div>

        {{-- ── El Proyecto ── --}}
        <div class="about__card about__card--project">
            <div style="text-align:center;margin-bottom:4px;">
                <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24"
                         fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                </div>
                <h1 class="about__card-title">El Proyecto</h1>
            </div>
            <p class="about__card-text">Además de estudiar en el Instituto Alan Turing, Toni y yo (Francisco) estamos trabajando en un proyecto que nos tiene bastante ilusionados. La idea surgió un día hablando de lo complicado que es a veces ir a eventos en sitios pequeños —como teatros locales, orquestas, musicales o salas independientes— y no tener ni idea de dónde te vas a sentar o cómo se va a ver el escenario.

            Queremos cambiar eso.

            Nuestro proyecto busca facilitar que las personas puedan reservar sus asientos de forma sencilla para este tipo de eventos, y que luego puedan pagar directamente en el lugar.

            Además, le estamos dando un toque extra: cada asiento tendrá imágenes reales o simuladas de cómo se ve el escenario desde esa posición, junto con comentarios de otros asistentes. Así, te evitas sorpresas tipo "me ha tocado detrás de una columna" o "no veo nada porque tengo una cabeza delante del tamaño de un balón de yoga".

            Yo me he encargado del backend, que me encanta, y Toni ha trabajado en el frontend para que todo sea intuitivo, limpio y fácil de usar. Nos complementamos bien: yo hago que todo funcione por dentro y el hace que se vea bonito por fuera.

            La idea es apoyar a los espacios más pequeños que no tienen herramientas tecnológicas tan potentes como los grandes teatros, pero que también merecen público, atención y comodidad. Si conseguimos que más gente se anime a ir a estos eventos sin miedo a "pillar mal sitio", ya habremos ganado algo.</p>
        </div>

    </div>
</div>

@endsection
