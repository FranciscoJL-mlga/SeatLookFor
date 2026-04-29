-- ============================================================
-- Asientos, zonas y asientos_evento para los 3 establecimientos
-- Ejecutar DESPUÉS de seed_eventos.sql
-- ============================================================

DO $$
DECLARE
    -- Establecimientos
    id_malaga   INTEGER;
    id_madrid   INTEGER;
    id_sevilla  INTEGER;

    -- Eventos por establecimiento
    ev_rey_leon     INTEGER;
    ev_shrek        INTEGER;
    ev_matilda      INTEGER;
    ev_grinch       INTEGER;
    ev_cascanueces  INTEGER;
    ev_brujo        INTEGER;

    -- Auxiliares
    r INTEGER;
    c INTEGER;
    v_idAsi INTEGER;

    -- Layout por zona
    -- Escenario: fila 0, cols 1-8  (precio 0)
    -- Zona A:    filas 1-2, cols 1-10  (precio 50)
    -- Zona B:    filas 3-5, cols 1-12  (precio 30)
    -- Zona C:    filas 6-9, cols 1-14  (precio 15)
BEGIN

    -- ── Obtener IDs de establecimientos ──────────────────────
    SELECT "idEst" INTO id_malaga  FROM establecimiento WHERE nombre = 'Teatro Cervantes'   AND demo = false LIMIT 1;
    SELECT "idEst" INTO id_madrid  FROM establecimiento WHERE nombre = 'Teatro Coliseum'    AND demo = false LIMIT 1;
    SELECT "idEst" INTO id_sevilla FROM establecimiento WHERE nombre = 'Teatro Lope de Vega' AND demo = false LIMIT 1;

    -- ── Obtener IDs de eventos ────────────────────────────────
    SELECT "idEve" INTO ev_rey_leon    FROM evento WHERE titulo = 'El Rey León'        LIMIT 1;
    SELECT "idEve" INTO ev_shrek       FROM evento WHERE titulo = 'Shrek el Musical'   LIMIT 1;
    SELECT "idEve" INTO ev_matilda     FROM evento WHERE titulo = 'Matilda el Musical'  LIMIT 1;
    SELECT "idEve" INTO ev_grinch      FROM evento WHERE titulo = 'El Grinch el Musical' LIMIT 1;
    SELECT "idEve" INTO ev_cascanueces FROM evento WHERE titulo = 'El Cascanueces'      LIMIT 1;
    SELECT "idEve" INTO ev_brujo       FROM evento WHERE titulo = 'El Brujo'            LIMIT 1;

    -- ===========================================================
    -- FUNCIÓN interna: insertar asientos + zonas + asientos_evento
    -- para un establecimiento y sus eventos
    -- ===========================================================

    -- ── MADRID (Teatro Coliseum) → Rey León + Shrek ───────────
    -- Zonas
    INSERT INTO zona (nombre, "idEst") VALUES ('A', id_madrid), ('B', id_madrid), ('C', id_madrid);

    -- Escenario
    FOR c IN 1..8 LOOP
        INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
        VALUES ('libre', 'escenario', c, 0, 0.00, id_madrid);
    END LOOP;

    -- Zona A (filas 1-2, cols 1-10, 50€)
    FOR r IN 1..2 LOOP
        FOR c IN 1..10 LOOP
            INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
            VALUES ('libre', 'A', c, r, 50.00, id_madrid);
        END LOOP;
    END LOOP;

    -- Zona B (filas 3-5, cols 1-12, 30€)
    FOR r IN 3..5 LOOP
        FOR c IN 1..12 LOOP
            INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
            VALUES ('libre', 'B', c, r, 30.00, id_madrid);
        END LOOP;
    END LOOP;

    -- Zona C (filas 6-9, cols 1-14, 15€)
    FOR r IN 6..9 LOOP
        FOR c IN 1..14 LOOP
            INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
            VALUES ('libre', 'C', c, r, 15.00, id_madrid);
        END LOOP;
    END LOOP;

    -- Vincular asientos (sin escenario) a eventos de Madrid
    INSERT INTO asientos_evento ("idAsi", "idEve", precio)
    SELECT a."idAsi",
           ev_rey_leon,
           a.precio
    FROM asiento a
    WHERE a."idEst" = id_madrid AND a.zona != 'escenario';

    INSERT INTO asientos_evento ("idAsi", "idEve", precio)
    SELECT a."idAsi",
           ev_shrek,
           a.precio
    FROM asiento a
    WHERE a."idEst" = id_madrid AND a.zona != 'escenario';

    -- ── MÁLAGA (Teatro Cervantes) → Matilda + Cascanueces ─────
    INSERT INTO zona (nombre, "idEst") VALUES ('A', id_malaga), ('B', id_malaga), ('C', id_malaga);

    FOR c IN 1..8 LOOP
        INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
        VALUES ('libre', 'escenario', c, 0, 0.00, id_malaga);
    END LOOP;

    FOR r IN 1..2 LOOP
        FOR c IN 1..10 LOOP
            INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
            VALUES ('libre', 'A', c, r, 50.00, id_malaga);
        END LOOP;
    END LOOP;

    FOR r IN 3..5 LOOP
        FOR c IN 1..12 LOOP
            INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
            VALUES ('libre', 'B', c, r, 30.00, id_malaga);
        END LOOP;
    END LOOP;

    FOR r IN 6..9 LOOP
        FOR c IN 1..14 LOOP
            INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
            VALUES ('libre', 'C', c, r, 15.00, id_malaga);
        END LOOP;
    END LOOP;

    INSERT INTO asientos_evento ("idAsi", "idEve", precio)
    SELECT a."idAsi", ev_matilda, a.precio
    FROM asiento a
    WHERE a."idEst" = id_malaga AND a.zona != 'escenario';

    INSERT INTO asientos_evento ("idAsi", "idEve", precio)
    SELECT a."idAsi", ev_cascanueces, a.precio
    FROM asiento a
    WHERE a."idEst" = id_malaga AND a.zona != 'escenario';

    -- ── SEVILLA (Teatro Lope de Vega) → Grinch + Brujo ───────
    INSERT INTO zona (nombre, "idEst") VALUES ('A', id_sevilla), ('B', id_sevilla), ('C', id_sevilla);

    FOR c IN 1..8 LOOP
        INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
        VALUES ('libre', 'escenario', c, 0, 0.00, id_sevilla);
    END LOOP;

    FOR r IN 1..2 LOOP
        FOR c IN 1..10 LOOP
            INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
            VALUES ('libre', 'A', c, r, 50.00, id_sevilla);
        END LOOP;
    END LOOP;

    FOR r IN 3..5 LOOP
        FOR c IN 1..12 LOOP
            INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
            VALUES ('libre', 'B', c, r, 30.00, id_sevilla);
        END LOOP;
    END LOOP;

    FOR r IN 6..9 LOOP
        FOR c IN 1..14 LOOP
            INSERT INTO asiento (estado, zona, "ejeX", "ejeY", precio, "idEst")
            VALUES ('libre', 'C', c, r, 15.00, id_sevilla);
        END LOOP;
    END LOOP;

    INSERT INTO asientos_evento ("idAsi", "idEve", precio)
    SELECT a."idAsi", ev_grinch, a.precio
    FROM asiento a
    WHERE a."idEst" = id_sevilla AND a.zona != 'escenario';

    INSERT INTO asientos_evento ("idAsi", "idEve", precio)
    SELECT a."idAsi", ev_brujo, a.precio
    FROM asiento a
    WHERE a."idEst" = id_sevilla AND a.zona != 'escenario';

END;
$$;
