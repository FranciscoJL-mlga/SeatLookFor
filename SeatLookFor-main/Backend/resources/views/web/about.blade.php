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
                <a href="https://www.linkedin.com/in/ENLACE-FRANCISCO" target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(10,102,194,0.12);border:1px solid rgba(10,102,194,0.35);border-radius:20px;font-size:12px;color:#4a9fd4;font-weight:600;text-decoration:none;transition:background 0.2s;"
                   onmouseover="this.style.background='rgba(10,102,194,0.25)'" onmouseout="this.style.background='rgba(10,102,194,0.12)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                    LinkedIn
                </a>
            </div>
            <p class="about__card-text">Desde pequeño me han interesado los videojuegos, especialmente los RPG, lo que despertó en mí la curiosidad por entender cómo funcionan los sistemas por dentro. Con el tiempo, esa curiosidad se transformó en una vocación por la programación, centrada principalmente en el desarrollo web.

Actualmente he finalizado mis estudios en Desarrollo de Aplicaciones Web y he trabajado en distintos proyectos utilizando PHP y Laravel, gestión de bases de datos y desarrollo de funcionalidades completas del lado del servidor. Disfruto especialmente diseñando la lógica que hay detrás de las aplicaciones y asegurando que todo funcione de forma eficiente y estructurada.

Además, tengo conocimientos en HTML, CSS, JavaScript, Python y SQL, así como experiencia con Angular y formación en React. También estoy familiarizado con entornos de despliegue en la nube como AWS.

Compagino mi formación tecnológica con experiencia laboral en el sector de la hostelería, lo que me ha permitido desarrollar habilidades como la responsabilidad, el trabajo en equipo y la capacidad de adaptación.

Me considero una persona constante, con gran capacidad de aprendizaje y motivada por seguir creciendo profesionalmente dentro del desarrollo de software.</p>
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
                <a href="https://www.linkedin.com/in/ENLACE-TONI" target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(10,102,194,0.12);border:1px solid rgba(10,102,194,0.35);border-radius:20px;font-size:12px;color:#4a9fd4;font-weight:600;text-decoration:none;transition:background 0.2s;"
                   onmouseover="this.style.background='rgba(10,102,194,0.25)'" onmouseout="this.style.background='rgba(10,102,194,0.12)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                    LinkedIn
                </a>
            </div>
            <p class="about__card-text">¡Ey! Soy Toni. Me considero una persona alegre, positiva y bastante realista. Me gusta ver el vaso medio lleno, pero tampoco me monto películas de Disney cuando sé que viene una semana con cuatro entregas y dos exámenes. La clave está en reírse, moverse y seguir.

            Me encanta el deporte. Juego al pádel siempre que puedo —aunque mis amigos dicen que a veces le hablo a la pala más que a ellos—, y también hago pesas. Vale, lo admito: no se nota mucho… pero están ahí, te lo juro. Lo importante es el esfuerzo, ¿no?

            Soy fan de la buena comida, sin muchas complicaciones. Si hay una buena pizza, una hamburguesa o unas tapas, estoy dentro. Y si después se puede caer una peli o una serie, mejor aún. Me puedo enganchar a cualquier cosa si tiene buen ritmo o personajes que molen. Desde thrillers a comedias tontas, soy de los que dicen "un capítulo más" y acaba viendo cuatro.

            Estudio programación en el Instituto Alan Turing. Allí conocí a gente muy crack, y también a otros que están igual de perdidos que yo cuando vemos un error de JavaScript. Pero lo bueno es que me encanta lo que hago, sobre todo el frontend. Me flipa todo lo que tiene que ver con diseño, colores, interacción… eso de ver cómo tus líneas de código se convierten en algo visual y funcional me parece magia pura.

            Intento llevarlo todo con humor, aunque a veces toque apretar los dientes. Soy de los que prefieren disfrutar el camino, aunque haya bugs, trabajos de grupo y días donde nada compila.</p>
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
