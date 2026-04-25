<x-layout.nav>
<style>
    .admin-form-wrap { background: var(--bg); min-height: 100vh; padding: 0; margin: -32px -24px; }
    .admin-form-inner { max-width: 1100px; margin: 0 auto; padding: 36px 24px; }
    .admin-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 28px 32px;
        margin-bottom: 24px;
    }
    .admin-card h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }
    .admin-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-dim) !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .admin-input {
        width: 100%;
        background: var(--bg-raised) !important;
        border: 1px solid var(--border) !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
        color: var(--text) !important;
        font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .admin-input:focus {
        outline: none !important;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px var(--primary-glow) !important;
    }
    .admin-input::placeholder { color: var(--text-dim) !important; }
    .admin-select { appearance: none; }
    .admin-btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 28px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .admin-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 0 20px var(--primary-glow); }
    .admin-btn-danger {
        background: rgba(239,68,68,0.15);
        color: #f87171;
        border: 1px solid rgba(239,68,68,0.3);
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
    }
    .admin-btn-danger:hover { background: rgba(239,68,68,0.3); }
    .admin-canvas-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .butaca { width: 46px; height: 50px; position: absolute; cursor: grab; user-select: none; }
    .butaca svg { display: block; transition: filter .15s; }
    .butaca:hover svg { filter: brightness(1.2) drop-shadow(0 0 4px rgba(139,92,246,.45)); }
    .grid-cell { width: 50px; height: 50px; border: 1px dashed rgba(139,92,246,0.2); box-sizing: border-box; }
    .grid-container { display: grid; grid-template-columns: repeat(auto-fill, 50px); grid-auto-rows: 50px; position: relative; }
    .escenario {
        width: 300px; height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white; font-size: 13px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        position: absolute; border-radius: 6px; cursor: move; letter-spacing: 2px;
    }
    .canvas-hint { font-size: 0.75rem; color: var(--text-dim); margin-bottom: 8px; display: none; }
    @media (max-width: 640px) { .canvas-hint { display: block; } }
</style>

<div class="admin-form-wrap">
    <div class="admin-form-inner">

        <div style="margin-bottom:28px;">
            <h1 style="font-family:'Poppins',sans-serif;font-size:1.6rem;font-weight:700;color:var(--text);">Crear Establecimiento</h1>
            <p style="color:var(--text-dim);font-size:0.875rem;margin-top:4px;">Define el nombre, ubicación e imagen del nuevo espacio y diseña el mapa de asientos.</p>
        </div>

        @if($errors->any())
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:10px;padding:14px 18px;margin-bottom:20px;">
                <ul style="margin:0;padding-left:16px;color:#f87171;font-size:0.875rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="formularioEstablecimiento" method="POST" action="{{ route('establecimiento.guardar') }}" enctype="multipart/form-data">
            @csrf

            <!-- Datos básicos -->
            <div class="admin-card">
                <h2>Datos del Establecimiento</h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;">
                    <div>
                        <label class="admin-label">Nombre</label>
                        <input type="text" name="nombre" required class="admin-input" placeholder="Ej: Teatro Real">
                    </div>
                    <div>
                        <label class="admin-label">Ubicación</label>
                        <input type="text" name="ubicacion" required class="admin-input" placeholder="Ej: Madrid, España">
                    </div>
                    <div>
                        <label class="admin-label">Imagen</label>
                        <input type="file" name="imagen" accept="image/*" required class="admin-input" style="padding:6px 14px;">
                    </div>
                </div>
            </div>

            <input type="hidden" name="asientos" id="inputAsientos">

            <!-- Editor de asientos -->
            <div class="admin-card">
                <h2>Editor de Asientos</h2>

                <div style="display:flex;flex-wrap:wrap;align-items:center;gap:12px;margin-bottom:20px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <label class="admin-label" style="margin:0;">Zona:</label>
                        <input type="text" id="zona" maxlength="50" placeholder="Nombre de zona" class="admin-input" style="width:180px;">
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <label class="admin-label" style="margin:0;">Modo:</label>
                        <select id="modo" class="admin-input admin-select" style="width:195px;">
                            <option value="add">Añadir (clic)</option>
                            <option value="paint">Pintar zona (arrastrar)</option>
                            <option value="move">Mover</option>
                        </select>
                    </div>
                    <button id="deshacer" type="button" class="admin-btn-danger" style="margin-left:auto;">
                        ↩ Deshacer (Ctrl+Z)
                    </button>
                </div>

                <p style="font-size:0.72rem;color:var(--text-dim);margin-bottom:10px;line-height:1.6;">
                    <strong style="color:var(--text);">Añadir</strong>: clic para colocar un asiento ·
                    <strong style="color:var(--text);">Pintar</strong>: arrastra un rectángulo para rellenar una zona ·
                    <strong style="color:var(--text);">Mover</strong>: arrastra butacas ·
                    <strong style="color:var(--text);">Clic derecho</strong>: borrar asiento
                </p>
                <p class="canvas-hint">💡 Desliza horizontalmente para ver el editor completo.</p>

                <div class="admin-canvas-wrap">
                    <div id="canvas"
                         class="relative grid-container"
                         style="width:1000px;height:600px;background:var(--bg-raised);border:1px solid var(--border);border-radius:12px;overflow:hidden;grid-template-columns:repeat(20,50px);">
                    </div>
                </div>
            </div>

            <div style="text-align:center;margin-top:8px;">
                <button type="submit" class="admin-btn-primary">
                    💾 Guardar Establecimiento
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const canvas    = document.getElementById('canvas');
    const zonaInput = document.getElementById('zona');
    const modoInput = document.getElementById('modo');
    const deshacerBtn = document.getElementById('deshacer');

    const CELL = 50, FILAS = 12, COLS = 20;
    const COLORS = { A:'#8b5cf6', B:'#3b82f6', C:'#10b981', D:'#f59e0b', E:'#ef4444', F:'#ec4899' };

    let modo = 'add';
    let seatCount = 1;
    const history = [];
    const occupiedCells = new Set(); // "ejeX,ejeY"

    /* ── Build grid ── */
    for (let i = 0; i < FILAS * COLS; i++) {
        const cell = document.createElement('div');
        cell.classList.add('grid-cell');
        canvas.appendChild(cell);
    }

    modoInput.addEventListener('change', e => {
        modo = e.target.value;
        canvas.style.cursor = modo === 'paint' ? 'crosshair' : 'default';
    });

    /* ── Helpers ── */
    function posKey(x, y) { return `${x},${y}`; }

    function cellAt(px, py) {
        return {
            ejeX: Math.max(0, Math.min(COLS  - 1, Math.floor(px / CELL))),
            ejeY: Math.max(0, Math.min(FILAS - 1, Math.floor(py / CELL)))
        };
    }

    function seatSVG(zona) {
        const color = COLORS[zona.toUpperCase()] || '#6366f1';
        return `<svg viewBox="0 0 44 48" width="44" height="48" xmlns="http://www.w3.org/2000/svg">
            <rect x="5" y="0" width="34" height="29" rx="7" fill="${color}"/>
            <rect x="0" y="32" width="44" height="14" rx="5" fill="${color}"/>
            <text x="22" y="20" text-anchor="middle" font-size="10" font-weight="bold" fill="white" font-family="sans-serif">${zona}</text>
        </svg>`;
    }

    function createSeat(zona, ejeX, ejeY) {
        const key = posKey(ejeX, ejeY);
        if (occupiedCells.has(key)) return null; // celda ocupada
        occupiedCells.add(key);

        const div = document.createElement('div');
        div.className = 'butaca';
        div.innerHTML = seatSVG(zona);
        div.style.left = `${ejeX * CELL + 5}px`;
        div.style.top  = `${ejeY * CELL + 5}px`;
        div.dataset.zona = zona;
        div.dataset.x    = ejeX;
        div.dataset.y    = ejeY;
        makeDraggable(div);
        bindRightClick(div);
        canvas.appendChild(div);
        seatCount++;
        return div;
    }

    /* ── Modo: Añadir (clic) ── */
    canvas.addEventListener('click', e => {
        if (modo !== 'add') return;
        const zona = zonaInput.value.trim();
        if (!zona) return alert('Primero escribe una zona');
        const rect = canvas.getBoundingClientRect();
        const { ejeX, ejeY } = cellAt(e.clientX - rect.left, e.clientY - rect.top);
        const div = createSeat(zona, ejeX, ejeY);
        if (div) history.push({ tipo: 'add', elements: [div] });
    });

    /* ── Modo: Pintar zona (arrastrar rectángulo) ── */
    let painting = false, paintStart = null, paintOverlay = null;

    canvas.addEventListener('mousedown', e => {
        if (modo !== 'paint') return;
        const zona = zonaInput.value.trim();
        if (!zona) { alert('Primero escribe una zona'); return; }
        e.preventDefault();
        painting = true;
        const rect = canvas.getBoundingClientRect();
        paintStart = cellAt(e.clientX - rect.left, e.clientY - rect.top);
        paintOverlay = document.createElement('div');
        paintOverlay.style.cssText = 'position:absolute;background:rgba(139,92,246,0.18);border:2px dashed rgba(139,92,246,0.55);border-radius:4px;pointer-events:none;z-index:50;';
        canvas.appendChild(paintOverlay);
        updateOverlay(paintStart, paintStart);
    });

    document.addEventListener('mousemove', e => {
        if (!painting || !paintOverlay) return;
        const rect = canvas.getBoundingClientRect();
        updateOverlay(paintStart, cellAt(e.clientX - rect.left, e.clientY - rect.top));
    });

    document.addEventListener('mouseup', e => {
        if (!painting) return;
        painting = false;
        const zona = zonaInput.value.trim();
        const rect = canvas.getBoundingClientRect();
        const end = cellAt(e.clientX - rect.left, e.clientY - rect.top);
        paintOverlay?.remove(); paintOverlay = null;

        const x1 = Math.min(paintStart.ejeX, end.ejeX), x2 = Math.max(paintStart.ejeX, end.ejeX);
        const y1 = Math.min(paintStart.ejeY, end.ejeY), y2 = Math.max(paintStart.ejeY, end.ejeY);

        const added = [];
        for (let row = y1; row <= y2; row++)
            for (let col = x1; col <= x2; col++) {
                const div = createSeat(zona, col, row);
                if (div) added.push(div);
            }
        if (added.length) history.push({ tipo: 'add', elements: added });
    });

    function updateOverlay(start, end) {
        if (!paintOverlay) return;
        const x1 = Math.min(start.ejeX, end.ejeX), x2 = Math.max(start.ejeX, end.ejeX);
        const y1 = Math.min(start.ejeY, end.ejeY), y2 = Math.max(start.ejeY, end.ejeY);
        paintOverlay.style.left   = `${x1 * CELL}px`;
        paintOverlay.style.top    = `${y1 * CELL}px`;
        paintOverlay.style.width  = `${(x2 - x1 + 1) * CELL}px`;
        paintOverlay.style.height = `${(y2 - y1 + 1) * CELL}px`;
    }

    /* ── Mover ── */
    function makeDraggable(el) {
        let dragging = false, ox, oy, origX, origY;
        el.addEventListener('mousedown', e => {
            if (modo !== 'move') return;
            e.stopPropagation();
            dragging = true; ox = e.offsetX; oy = e.offsetY;
            origX = parseInt(el.dataset.x); origY = parseInt(el.dataset.y);
            el.style.zIndex = 1000;
        });
        document.addEventListener('mousemove', e => {
            if (!dragging) return;
            const rect = canvas.getBoundingClientRect();
            const ejeX = Math.max(0, Math.min(COLS  - 1, Math.round((e.clientX - rect.left - ox) / CELL)));
            const ejeY = Math.max(0, Math.min(FILAS - 1, Math.round((e.clientY - rect.top  - oy) / CELL)));
            el.style.left = `${ejeX * CELL + 5}px`;
            el.style.top  = `${ejeY * CELL + 5}px`;
            el.dataset.x = ejeX; el.dataset.y = ejeY;
        });
        document.addEventListener('mouseup', () => {
            if (!dragging) return;
            dragging = false; el.style.zIndex = '';
            const newX = parseInt(el.dataset.x), newY = parseInt(el.dataset.y);
            const newKey = posKey(newX, newY), origKey = posKey(origX, origY);
            if (newKey === origKey) return;
            // Si la celda destino está ocupada por otro asiento, revertir
            if (occupiedCells.has(newKey)) {
                el.dataset.x = origX; el.dataset.y = origY;
                el.style.left = `${origX * CELL + 5}px`;
                el.style.top  = `${origY * CELL + 5}px`;
            } else {
                occupiedCells.delete(origKey);
                occupiedCells.add(newKey);
                history.push({ tipo: 'move', element: el, oldX: origX, oldY: origY });
            }
        });
    }

    /* ── Borrar con clic derecho ── */
    function bindRightClick(el) {
        el.addEventListener('contextmenu', e => {
            e.preventDefault();
            const ejeX = parseInt(el.dataset.x), ejeY = parseInt(el.dataset.y);
            occupiedCells.delete(posKey(ejeX, ejeY));
            el.remove();
            history.push({ tipo: 'remove', element: el, ejeX, ejeY });
        });
    }

    /* ── Deshacer ── */
    function deshacerUltimaAccion() {
        const last = history.pop();
        if (!last) return;
        if (last.tipo === 'add') {
            (last.elements || []).forEach(el => {
                occupiedCells.delete(posKey(parseInt(el.dataset.x), parseInt(el.dataset.y)));
                el.remove();
            });
        } else if (last.tipo === 'move') {
            occupiedCells.delete(posKey(parseInt(last.element.dataset.x), parseInt(last.element.dataset.y)));
            occupiedCells.add(posKey(last.oldX, last.oldY));
            last.element.style.left = `${last.oldX * CELL + 5}px`;
            last.element.style.top  = `${last.oldY * CELL + 5}px`;
            last.element.dataset.x  = last.oldX;
            last.element.dataset.y  = last.oldY;
        } else if (last.tipo === 'remove') {
            occupiedCells.add(posKey(last.ejeX, last.ejeY));
            canvas.appendChild(last.element);
        }
    }

    deshacerBtn.addEventListener('click', deshacerUltimaAccion);
    document.addEventListener('keydown', e => { if (e.ctrlKey && e.key === 'z') { e.preventDefault(); deshacerUltimaAccion(); } });

    /* ── Submit ── */
    document.getElementById('formularioEstablecimiento').addEventListener('submit', function () {
        const resultado = [];
        document.querySelectorAll('.butaca').forEach(el => {
            resultado.push({ estado: 'libre', zona: el.dataset.zona, ejeX: parseInt(el.dataset.x), ejeY: parseInt(el.dataset.y), precio: 0.00 });
        });
        document.getElementById('inputAsientos').value = JSON.stringify(resultado);
    });
</script>
</x-layout.nav>
