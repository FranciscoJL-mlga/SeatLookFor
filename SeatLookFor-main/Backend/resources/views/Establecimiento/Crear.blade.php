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
                        <input type="text" id="zona" maxlength="5" placeholder="Máx. 5 car." class="admin-input" style="width:120px;">
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <label class="admin-label" style="margin:0;">Modo:</label>
                        <select id="modo" class="admin-input admin-select" style="width:160px;">
                            <option value="add">Añadir asientos</option>
                            <option value="move">Mover</option>
                        </select>
                    </div>
                    <button id="deshacer" type="button" class="admin-btn-danger" style="margin-left:auto;">
                        ↩ Deshacer (Ctrl+Z)
                    </button>
                </div>

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
    const canvas = document.getElementById('canvas');
    const zonaInput = document.getElementById('zona');
    const modoInput = document.getElementById('modo');
    const deshacerBtn = document.getElementById('deshacer');

    let modo = 'add';
    let seatCount = 1;
    let escenarioEl = null;
    const history = [];

    const filas = 12, columnas = 20;
    for (let i = 0; i < filas * columnas; i++) {
        const cell = document.createElement('div');
        cell.classList.add('grid-cell');
        canvas.appendChild(cell);
    }

    modoInput.addEventListener('change', (e) => { modo = e.target.value; });

    canvas.addEventListener('click', (e) => {
        const rect = canvas.getBoundingClientRect();
        const x = Math.floor((e.clientX - rect.left) / 50) * 50 + 5;
        const y = Math.floor((e.clientY - rect.top) / 50) * 50 + 5;

        if (modo === 'add') {
            const zona = zonaInput.value.trim();
            if (!zona) return alert("Primero escribe una zona");
            const COLORS = { 'A':'#8b5cf6','B':'#3b82f6','C':'#10b981','D':'#f59e0b','E':'#ef4444','F':'#ec4899' };
            const color = COLORS[zona.toUpperCase()] || '#6366f1';
            const codigo = `${zona}-${seatCount}`;
            const div = document.createElement('div');
            div.className = 'butaca';
            div.innerHTML = `<svg viewBox="0 0 44 48" width="44" height="48" xmlns="http://www.w3.org/2000/svg">
                <rect x="5" y="0" width="34" height="29" rx="7" fill="${color}"/>
                <rect x="0" y="32" width="44" height="14" rx="5" fill="${color}"/>
                <text x="22" y="20" text-anchor="middle" font-size="10" font-weight="bold" fill="white" font-family="sans-serif">${zona}</text>
            </svg>`;
            div.style.left = `${x}px`;
            div.style.top = `${y}px`;
            div.dataset.codigo = codigo;
            div.dataset.zona = zona;
            div.dataset.x = x / 50;
            div.dataset.y = y / 50;
            makeDraggable(div);
            canvas.appendChild(div);
            history.push({ tipo: 'add', element: div });
            seatCount++;
        } else if (modo === 'stage') {
            if (!escenarioEl) {
                escenarioEl = document.createElement('div');
                escenarioEl.className = 'escenario';
                escenarioEl.innerText = 'ESCENARIO';
                escenarioEl.style.left = `${x}px`;
                escenarioEl.style.top = `${y}px`;
                canvas.appendChild(escenarioEl);
                makeStageDraggable(escenarioEl);
            } else {
                escenarioEl.style.left = `${x}px`;
                escenarioEl.style.top = `${y}px`;
            }
        }
    });

    function makeDraggable(el) {
        let isDragging = false, offsetX, offsetY, originalX, originalY;
        el.addEventListener('mousedown', (e) => {
            if (modo !== 'move') return;
            isDragging = true; offsetX = e.offsetX; offsetY = e.offsetY;
            originalX = parseInt(el.style.left); originalY = parseInt(el.style.top);
            el.style.zIndex = 1000;
        });
        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const rect = canvas.getBoundingClientRect();
            let x = Math.round((e.clientX - rect.left - offsetX) / 50) * 50 + 5;
            let y = Math.round((e.clientY - rect.top - offsetY) / 50) * 50 + 5;
            el.style.left = `${x}px`; el.style.top = `${y}px`;
            el.dataset.x = x / 50; el.dataset.y = y / 50;
        });
        document.addEventListener('mouseup', () => {
            if (isDragging) {
                const newX = parseInt(el.style.left), newY = parseInt(el.style.top);
                if (originalX !== newX || originalY !== newY)
                    history.push({ tipo: 'move', element: el, oldX: originalX / 50, oldY: originalY / 50 });
            }
            isDragging = false; el.style.zIndex = '';
        });
    }

    function makeStageDraggable(el) {
        let isDragging = false, offsetX, offsetY;
        el.addEventListener('mousedown', (e) => {
            if (modo !== 'stage') return;
            isDragging = true; offsetX = e.offsetX; offsetY = e.offsetY; el.style.zIndex = 1000;
        });
        document.addEventListener('mousemove', (e) => {
            if (!isDragging || modo !== 'stage') return;
            const rect = canvas.getBoundingClientRect();
            el.style.left = `${Math.round((e.clientX - rect.left - offsetX) / 50) * 50 + 5}px`;
            el.style.top  = `${Math.round((e.clientY - rect.top  - offsetY) / 50) * 50 + 5}px`;
        });
        document.addEventListener('mouseup', () => { isDragging = false; el.style.zIndex = ''; });
    }

    function deshacerUltimaAccion() {
        const last = history.pop();
        if (!last) return;
        if (last.tipo === 'add') last.element.remove();
        else if (last.tipo === 'move') {
            last.element.style.left = `${last.oldX * 50}px`;
            last.element.style.top  = `${last.oldY * 50}px`;
            last.element.dataset.x  = last.oldX;
            last.element.dataset.y  = last.oldY;
        }
    }

    deshacerBtn.addEventListener('click', deshacerUltimaAccion);
    document.addEventListener('keydown', (e) => { if (e.ctrlKey && e.key === 'z') { e.preventDefault(); deshacerUltimaAccion(); } });

    document.getElementById('formularioEstablecimiento').addEventListener('submit', function () {
        const resultado = [];
        document.querySelectorAll('.butaca').forEach(el => {
            resultado.push({ estado: 'libre', zona: el.dataset.zona, ejeX: parseInt(el.dataset.x), ejeY: parseInt(el.dataset.y), precio: 0.00 });
        });
        if (escenarioEl) {
            resultado.push({ estado: 'ocupado', zona: 'escenario', ejeX: parseInt(escenarioEl.style.left), ejeY: parseInt(escenarioEl.style.top), precio: 0.00 });
        }
        document.getElementById('inputAsientos').value = JSON.stringify(resultado);
    });
</script>
</x-layout.nav>
