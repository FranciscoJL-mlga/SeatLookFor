<x-layout.nav>
<style>
    .admin-form-wrap { background: var(--bg); min-height: 100vh; padding: 0; margin: -32px -24px; }
    .admin-form-inner { max-width: 900px; margin: 0 auto; padding: 36px 24px; }
    .admin-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 28px 32px;
        margin-bottom: 24px;
    }
    .admin-card h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
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
        font-size: 0.78rem;
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
    .admin-select option { background: var(--bg-raised); color: var(--text); }
    .admin-btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 13px 32px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
        width: 100%;
    }
    .admin-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 0 20px var(--primary-glow); }
    #zonas-content label { color: var(--text-dim) !important; font-weight: 600; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block; }
    #zonas-content input {
        color: var(--text) !important;
        background: var(--bg-raised) !important;
        border: 1px solid var(--border) !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
        width: 100%;
    }
    #mapa-asientos-container h2 { color: var(--text) !important; }
</style>

<div class="admin-form-wrap">
    <div class="admin-form-inner">

        <div style="margin-bottom:28px;">
            <h1 style="font-family:'Poppins',sans-serif;font-size:1.6rem;font-weight:700;color:var(--text);">Crear Evento</h1>
            <p style="color:var(--text-dim);font-size:0.875rem;margin-top:4px;">Rellena los datos del evento, selecciona el establecimiento y elige los asientos disponibles.</p>
        </div>

        @if(session('success'))
            <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:10px;padding:14px 18px;margin-bottom:20px;color:#86efac;font-size:0.875rem;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:10px;padding:14px 18px;margin-bottom:20px;">
                <ul style="margin:0;padding-left:16px;color:#f87171;font-size:0.875rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('eventos.guardar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Información básica -->
            <div class="admin-card">
                <h2>Información del Evento</h2>

                <div style="margin-bottom:16px;">
                    <label class="admin-label">Título</label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}" required class="admin-input" placeholder="Ej: La Traviata (Ópera)">
                </div>

                <div style="margin-bottom:16px;">
                    <label class="admin-label">Descripción</label>
                    <textarea name="descripcion" rows="3" required class="admin-input" placeholder="Describe el evento..." style="resize:vertical;">{{ old('descripcion') }}</textarea>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:16px;">
                    <div>
                        <label class="admin-label">Fecha</label>
                        <input type="date" name="fecha" value="{{ old('fecha') }}" required class="admin-input">
                    </div>
                    <div>
                        <label class="admin-label">Hora de inicio</label>
                        <input type="time" name="hora" value="{{ old('hora') }}" required class="admin-input">
                    </div>
                    <div>
                        <label class="admin-label">Duración</label>
                        <input type="time" name="duracion" value="{{ old('duracion') }}" required class="admin-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;">
                    <div>
                        <label class="admin-label">Tipo</label>
                        <select name="tipo" required class="admin-input admin-select">
                            <option value="">Selecciona...</option>
                            <option value="Teatro"   {{ old('tipo')==='Teatro'    ? 'selected' : '' }}>Teatro</option>
                            <option value="Orquesta" {{ old('tipo')==='Orquesta'  ? 'selected' : '' }}>Orquesta</option>
                            <option value="Musical"  {{ old('tipo')==='Musical'   ? 'selected' : '' }}>Musical</option>
                            <option value="Concierto"{{ old('tipo')==='Concierto' ? 'selected' : '' }}>Concierto</option>
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Categoría</label>
                        <select name="categoria" required class="admin-input admin-select">
                            <option value="">Selecciona...</option>
                            @foreach(['Drama','Familiar','Clásica','Musical','Barroco','Fantasía','Suspenso','Comedia'] as $cat)
                                <option value="{{ $cat }}" {{ old('categoria')===$cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Imagen portada</label>
                        <input type="file" name="imagen" accept="image/*" class="admin-input" style="padding:6px 14px;">
                    </div>
                </div>
            </div>

            <!-- Establecimiento y asientos -->
            <div class="admin-card">
                <h2>Establecimiento y Asientos</h2>

                <div style="margin-bottom:20px;">
                    <label class="admin-label">Establecimiento</label>
                    <select name="establecimiento_id" id="establecimiento_id" required class="admin-input admin-select">
                        <option value="">Seleccione un establecimiento</option>
                        @foreach ($establecimientos as $est)
                            <option value="{{ $est->idEst }}">{{ $est->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Zonas y precios -->
                <div id="zonas-content" class="space-y-4"></div>

                <!-- Mapa de asientos -->
                <div id="mapa-asientos-container" class="mt-6 hidden">
                    <h2 style="font-family:'Poppins',sans-serif;font-size:1rem;font-weight:700;color:var(--text);text-align:center;margin-bottom:16px;">Mapa de Asientos</h2>
                    <p style="font-size:0.75rem;color:var(--text-dim);margin-bottom:8px;display:none;" class="sm:hidden text-center">
                        💡 Desliza horizontalmente para ver todos los asientos.
                    </p>
                    <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                        <div id="mapa-asientos"
                             style="position:relative;background:var(--bg-raised);border:1px solid var(--border);border-radius:12px;padding:16px;width:1000px;height:600px;"></div>
                    </div>
                </div>

                <div id="asientos-seleccionados-container"></div>

                @error('asientos_seleccionados')
                    <p style="color:#f87171;font-size:0.85rem;margin-top:10px;text-align:center;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="admin-btn-primary">
                🎭 Guardar Evento
            </button>
        </form>
    </div>
</div>

<script>
const COLOR_ON  = '#10b981'; // verde  — asiento incluido en el evento
const COLOR_OFF = '#4b5563'; // gris   — asiento excluido

document.getElementById('establecimiento_id').addEventListener('change', function () {
    const idEst = this.value;
    const zonasContent           = document.getElementById('zonas-content');
    const mapaContainer          = document.getElementById('mapa-asientos-container');
    const mapaAsientos           = document.getElementById('mapa-asientos');
    const asientoInputContainer  = document.getElementById('asientos-seleccionados-container');

    zonasContent.innerHTML = '<p style="color:var(--text-dim);font-size:0.875rem;">Cargando zonas...</p>';
    mapaAsientos.innerHTML = '';
    asientoInputContainer.innerHTML = '';
    mapaContainer.classList.add('hidden');

    if (!idEst) return;

    /* ── Helpers de hidden inputs ── */
    function addInputs(asientoId, zonaId) {
        if (asientoInputContainer.querySelector(`input[name='asientos_seleccionados[]'][value='${asientoId}']`)) return;
        const iId = document.createElement('input');
        iId.type = 'hidden'; iId.name = 'asientos_seleccionados[]'; iId.value = asientoId;
        const iZona = document.createElement('input');
        iZona.type = 'hidden'; iZona.name = `asientos_zonas[${asientoId}]`; iZona.value = zonaId;
        asientoInputContainer.append(iId, iZona);
    }
    function removeInputs(asientoId) {
        asientoInputContainer.querySelector(`input[name='asientos_seleccionados[]'][value='${asientoId}']`)?.remove();
        asientoInputContainer.querySelector(`input[name='asientos_zonas[${asientoId}]']`)?.remove();
    }

    /* ── Cambiar estado de un asiento (on = incluido) ── */
    function setAsientoState(wrap, on) {
        wrap.dataset.on = on ? 'true' : 'false';
        wrap.querySelectorAll('rect').forEach(r => r.setAttribute('fill', on ? COLOR_ON : COLOR_OFF));
        if (on) addInputs(wrap.dataset.id, wrap.dataset.zonaId);
        else    removeInputs(wrap.dataset.id);
    }

    fetch(`/admin/zonas-por-establecimiento/${idEst}`)
        .then(r => r.json())
        .then(zonas => {
            zonasContent.innerHTML = '';
            mapaAsientos.innerHTML = '';
            mapaContainer.classList.remove('hidden');

            zonas.forEach(zona => {

                /* ── Fila de zona: precio + botón de toggle ── */
                const row = document.createElement('div');
                row.style.cssText = 'display:flex;align-items:flex-end;gap:12px;margin-bottom:16px;flex-wrap:wrap;';
                row.innerHTML = `
                    <div style="flex:1;min-width:160px;">
                        <label>Zona ${zona.nombre} — Precio (€)</label>
                        <input type="hidden" name="zonas[]" value="${zona.idZona}">
                        <input type="number" name="precios[]" step="0.01" min="0" required
                               placeholder="Precio zona ${zona.nombre}">
                    </div>
                    <button type="button"
                            data-zona="${zona.nombre}" data-zona-id="${zona.idZona}" data-state="on"
                            style="padding:10px 16px;border-radius:8px;border:1px solid rgba(239,68,68,0.4);
                                   background:rgba(239,68,68,0.12);color:#f87171;
                                   font-size:0.8rem;font-weight:600;cursor:pointer;white-space:nowrap;flex-shrink:0;">
                        Excluir zona ${zona.nombre}
                    </button>`;
                zonasContent.appendChild(row);

                /* ── Botón de toggle de zona ── */
                const btn = row.querySelector('button');
                btn.addEventListener('click', () => {
                    const turningOff = btn.dataset.state === 'on';
                    mapaAsientos.querySelectorAll(`.asiento-wrap[data-zona="${zona.nombre}"]`).forEach(w => {
                        setAsientoState(w, !turningOff);
                    });
                    btn.dataset.state = turningOff ? 'off' : 'on';
                    if (turningOff) {
                        btn.textContent = `Incluir zona ${zona.nombre}`;
                        btn.style.borderColor = 'rgba(34,197,94,0.4)';
                        btn.style.background  = 'rgba(34,197,94,0.12)';
                        btn.style.color       = '#86efac';
                    } else {
                        btn.textContent = `Excluir zona ${zona.nombre}`;
                        btn.style.borderColor = 'rgba(239,68,68,0.4)';
                        btn.style.background  = 'rgba(239,68,68,0.12)';
                        btn.style.color       = '#f87171';
                    }
                });

                /* ── Asientos de la zona (todos incluidos por defecto) ── */
                zona.asientos.forEach(asiento => {
                    const wrap = document.createElement('div');
                    wrap.className = 'asiento-wrap';
                    wrap.style.cssText = `position:absolute;width:46px;height:50px;cursor:pointer;
                        left:${asiento.ejeX * 50 + 5}px;top:${asiento.ejeY * 50 + 5}px;`;
                    wrap.dataset.id     = asiento.id;
                    wrap.dataset.zona   = zona.nombre;
                    wrap.dataset.zonaId = zona.idZona;
                    wrap.dataset.on     = 'true';
                    wrap.title = `Zona ${asiento.zona} — Fila ${asiento.ejeY + 1}, Col ${asiento.ejeX + 1}`;

                    wrap.innerHTML = `<svg viewBox="0 0 44 48" width="44" height="48" xmlns="http://www.w3.org/2000/svg">
                        <rect x="5" y="0" width="34" height="29" rx="7" fill="${COLOR_ON}"/>
                        <rect x="0" y="32" width="44" height="14" rx="5" fill="${COLOR_ON}"/>
                    </svg>`;

                    /* Incluir por defecto */
                    addInputs(asiento.id, zona.idZona);

                    wrap.addEventListener('mouseenter', () => {
                        wrap.querySelector('svg').style.filter = wrap.dataset.on === 'true'
                            ? 'brightness(1.15) drop-shadow(0 0 4px rgba(16,185,129,.5))'
                            : 'brightness(1.4)';
                    });
                    wrap.addEventListener('mouseleave', () => {
                        wrap.querySelector('svg').style.filter = '';
                    });
                    wrap.addEventListener('click', () => {
                        setAsientoState(wrap, wrap.dataset.on !== 'true');
                    });

                    mapaAsientos.appendChild(wrap);
                });
            });

            /* ── Leyenda ── */
            const leyenda = document.createElement('div');
            leyenda.style.cssText = 'margin-top:14px;display:flex;gap:16px;font-size:0.75rem;color:var(--text-dim);';
            leyenda.innerHTML = `
                <span style="display:flex;align-items:center;gap:5px;">
                    <span style="width:12px;height:12px;border-radius:3px;background:${COLOR_ON};display:inline-block;"></span> Incluido
                </span>
                <span style="display:flex;align-items:center;gap:5px;">
                    <span style="width:12px;height:12px;border-radius:3px;background:${COLOR_OFF};display:inline-block;"></span> Excluido
                </span>
                <span style="color:var(--text-dim);">Clic en un asiento para incluirlo/excluirlo individualmente</span>`;
            document.getElementById('mapa-asientos-container').appendChild(leyenda);
        })
        .catch(() => {
            zonasContent.innerHTML = '<p style="color:#f87171;font-size:0.875rem;">Error al cargar zonas.</p>';
        });
});
</script>
</x-layout.nav>
