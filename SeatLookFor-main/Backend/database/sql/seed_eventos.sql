-- ============================================================
-- Establecimientos + Eventos de muestra para SeatLookFor
-- Ejecutar en PostgreSQL: psql -d <bbdd> -f seed_eventos.sql
-- ============================================================

DO $$
DECLARE
    id_malaga   INTEGER;
    id_madrid   INTEGER;
    id_sevilla  INTEGER;
BEGIN

    -- ── Establecimientos ──────────────────────────────────────
    INSERT INTO establecimiento (nombre, ubicacion, imagen, demo)
    VALUES ('Teatro Cervantes', 'C/ Ramos Marín s/n, Málaga', 'images/establecimientos/default.jpg', false)
    RETURNING "idEst" INTO id_malaga;

    INSERT INTO establecimiento (nombre, ubicacion, imagen, demo)
    VALUES ('Teatro Coliseum', 'Gran Vía 78, Madrid', 'images/establecimientos/default.jpg', false)
    RETURNING "idEst" INTO id_madrid;

    INSERT INTO establecimiento (nombre, ubicacion, imagen, demo)
    VALUES ('Teatro Lope de Vega', 'Av. María Luisa s/n, Sevilla', 'images/establecimientos/default.jpg', false)
    RETURNING "idEst" INTO id_sevilla;

    -- ── Eventos ───────────────────────────────────────────────
    INSERT INTO evento (titulo, estado, valoracion, ubicacion, tipo, descripcion, portada, duracion, fecha, categoria, "idEst", codigo, demo)
    VALUES
    (
        'El Rey León',
        'activo', '4.9',
        'Gran Vía 78, Madrid',
        'Musical',
        'El musical más emocionante de Broadway llega a España. Basado en la película de Disney, con música de Elton John y letra de Tim Rice, esta producción espectacular te transportará al corazón de África en una noche única e inolvidable.',
        'images/eventos/1749474112_el-rey-leon.jpg',
        '02:30:00', '2026-09-20', 'Musical',
        id_madrid,
        substr(md5(random()::text), 1, 8), false
    ),
    (
        'Shrek el Musical',
        'activo', '4.7',
        'Gran Vía 78, Madrid',
        'Musical',
        'El ogro más querido del cine llega al teatro en una producción llena de humor, emoción y grandes canciones. Una historia sobre la amistad, el amor y aprender a aceptarse a uno mismo tal como es.',
        'images/eventos/1749508744_shrek.jpg',
        '02:15:00', '2026-10-10', 'Musical',
        id_madrid,
        substr(md5(random()::text), 1, 8), false
    ),
    (
        'Matilda el Musical',
        'activo', '4.8',
        'C/ Ramos Marín s/n, Málaga',
        'Musical',
        'Basada en el libro de Roald Dahl, Matilda es una niña extraordinaria con un don especial. Con música y letras de Tim Minchin, este espectáculo ganador del Olivier Award es una celebración de la valentía y la imaginación.',
        'images/eventos/1749891842_matilda.jpg',
        '02:20:00', '2026-11-05', 'Musical',
        id_malaga,
        substr(md5(random()::text), 1, 8), false
    ),
    (
        'El Grinch el Musical',
        'activo', '4.6',
        'Av. María Luisa s/n, Sevilla',
        'Musical',
        'La Navidad llega al teatro con el personaje más refunfuñón de la historia. Una producción familiar llena de magia, color y canciones que te pondrán en el espíritu navideño. Apta para todos los públicos.',
        'images/eventos/1749828692_elgrinch.jpg',
        '01:45:00', '2026-12-12', 'Musical',
        id_sevilla,
        substr(md5(random()::text), 1, 8), false
    ),
    (
        'El Cascanueces',
        'activo', '4.8',
        'C/ Ramos Marín s/n, Málaga',
        'Ballet',
        'El ballet más representado del mundo llega en Navidad. La música de Tchaikovsky cobra vida en una producción deslumbrante con el Ballet Nacional. Una experiencia mágica para toda la familia que no te puedes perder.',
        'images/eventos/1749830103_386157_portada_el-cascanueces-y-el-rey-de-los-ratones_e-t-a-hoffmann_202310202313.jpg',
        '02:00:00', '2026-12-20', 'Ballet',
        id_malaga,
        substr(md5(random()::text), 1, 8), false
    ),
    (
        'El Brujo',
        'activo', '4.5',
        'Av. María Luisa s/n, Sevilla',
        'Monólogo',
        'Rafael Álvarez "El Brujo" regresa con un nuevo espectáculo de teatro clásico en clave de humor. Un viaje por los textos de Quevedo, Cervantes y Lope de Vega interpretados con la maestría y la genialidad que le caracterizan.',
        'images/eventos/1749815641_EL BRUJO.png',
        '01:30:00', '2026-10-25', 'Teatro',
        id_sevilla,
        substr(md5(random()::text), 1, 8), false
    );

END;
$$;
