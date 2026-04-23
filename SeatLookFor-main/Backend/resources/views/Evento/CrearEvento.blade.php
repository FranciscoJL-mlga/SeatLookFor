<x-layout.nav>

<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Crear Evento</h1>
    <style>
        /* Labels generados por JS también deben tener color */
        #zonas-content label { color: #374151; font-weight: 500; font-size: 0.875rem; margin-bottom: 0.3rem; display: block; }
        #zonas-content input { color: #111827; background: #fff; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; }
        #mapa-asientos-container h2 { color: #111827; }
    </style>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('eventos.guardar') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-6 py-6 space-y-5">
        @csrf

        <!-- Campos básicos del evento -->
        <div>
            <label for="titulo">Título</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" class="w-full border rounded" required>
        </div>

        <div>
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="w-full border rounded" required>{{ old('descripcion') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}" class="w-full border rounded" required>
            </div>
            <div>
                <label for="hora">Hora de inicio</label>
                <input type="time" name="hora" id="hora" value="{{ old('hora') }}" class="w-full border rounded" required>
            </div>
            <div>
                <label for="duracion">Duración</label>
                <input type="time" name="duracion" id="duracion" value="{{ old('duracion') }}" class="w-full border rounded" required>
            </div>
        </div>

        <div>
            <label for="establecimiento_id">Establecimiento</label>
            <select name="establecimiento_id" id="establecimiento_id" class="w-full border rounded" required>
                <option value="">Seleccione uno</option>
                @foreach ($establecimientos as $est)
                    <option value="{{ $est->idEst }}">{{ $est->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Contenedor de zonas y precios -->
        <div id="zonas-content" class="mt-6 space-y-4"></div>

        <!-- Mapa de asientos -->
        <div id="mapa-asientos-container" class="mt-6 hidden">
            <h2 class="text-xl font-semibold text-center mb-4">Mapa de Asientos</h2>
            <p class="text-xs text-gray-500 mb-2 sm:hidden text-center">
                <i class="bi bi-info-circle"></i> Desliza horizontalmente para ver todos los asientos.
            </p>
            <div class="overflow-x-auto">
                <div id="mapa-asientos" class="relative bg-gray-100 border rounded-xl p-4" style="width: 1000px; height: 600px;"></div>
            </div>
        </div>

        <!-- Contenedor para inputs ocultos -->
        <div id="asientos-seleccionados-container"></div>

        @error('asientos_seleccionados')
            <p class="text-red-600 text-sm mt-2 text-center">{{ $message }}</p>
        @enderror

        <div>
            <label for="tipo">Tipo</label>
            <select name="tipo" id="tipo" class="w-full border rounded" required>
                <option value="">Selecciona un tipo</option>
                <option value="Teatro">Teatro</option>
                <option value="Orquesta">Orquesta</option>
                <option value="Musical">Musical</option>
                <option value="Concierto">Concierto</option>
            </select>
        </div>

        <div>
            <label for="categoria">Categoría</label>
            <select name="categoria" id="categoria" class="w-full border rounded" required>
                <option value="">Selecciona una categoría</option>
                <option value="Drama">Drama</option>
                <option value="Familiar">Familiar</option>
                <option value="Clásica">Clásica</option>
                <option value="Musical">Musical</option>
                <option value="Barroco">Barroco</option>
                <option value="Fantasía">Fantasía</option>
                <option value="Suspenso">Suspenso</option>
                <option value="Comedia">Comedia</option>
            </select>
        </div>

        <div>
            <label for="imagen">Imagen</label>
            <input type="file" name="imagen" id="imagen" class="w-full" accept="image/*">
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Guardar Evento</button>
    </form>
</div>

<!-- Script para cargar zonas y asientos -->
<script>
document.getElementById('establecimiento_id').addEventListener('change', function () {
    const idEst = this.value;
    const zonasContent = document.getElementById('zonas-content');
    const mapaContainer = document.getElementById('mapa-asientos-container');
    const mapaAsientos = document.getElementById('mapa-asientos');
    const asientoInputContainer = document.getElementById('asientos-seleccionados-container');

    zonasContent.innerHTML = '<p>Cargando zonas...</p>';
    mapaAsientos.innerHTML = '';
    asientoInputContainer.innerHTML = '';
    mapaContainer.classList.add('hidden');

    if (!idEst) return;

    fetch(`/admin/zonas-por-establecimiento/${idEst}`)
        .then(response => response.json())
        .then(zonas => {
            zonasContent.innerHTML = '';
            mapaAsientos.innerHTML = '';
            mapaContainer.classList.remove('hidden');

            zonas.forEach(zona => {
                // Input de precio por zona
                zonasContent.innerHTML += `
                    <div class="mb-4">
                        <label>${zona.nombre} - Precio</label>
                        <input type="hidden" name="zonas[]" value="${zona.idZona}">
                        <input type="number" name="precios[]" step="0.01" class="w-full border rounded" required placeholder="Precio zona ${zona.nombre}">
                    </div>
                `;

                zona.asientos.forEach(asiento => {
                    const COLOR_FREE     = '#10b981';
                    const COLOR_SELECTED = '#f59e0b';

                    const wrap = document.createElement('div');
                    wrap.style.cssText = `position:absolute;width:46px;height:50px;cursor:pointer;
                        left:${asiento.ejeX * 50 + 5}px;top:${asiento.ejeY * 50 + 5}px;`;
                    wrap.dataset.id = asiento.id;
                    wrap.title = `Zona ${asiento.zona} — Fila ${asiento.ejeY}, Col ${asiento.ejeX}`;

                    const makeSVG = (color) => `
                        <svg viewBox="0 0 44 48" width="44" height="48" xmlns="http://www.w3.org/2000/svg"
                             style="display:block;transition:filter .15s;">
                            <rect x="5" y="0" width="34" height="29" rx="7" fill="${color}"/>
                            <rect x="0" y="32" width="44" height="14" rx="5" fill="${color}"/>
                        </svg>`;

                    wrap.innerHTML = makeSVG(COLOR_FREE);

                    wrap.addEventListener('mouseenter', () => {
                        if (!wrap.classList.contains('seleccionado'))
                            wrap.querySelector('svg').style.filter = 'brightness(1.2) drop-shadow(0 0 4px rgba(139,92,246,.5))';
                    });
                    wrap.addEventListener('mouseleave', () => {
                        if (!wrap.classList.contains('seleccionado'))
                            wrap.querySelector('svg').style.filter = '';
                    });

                    wrap.addEventListener('click', () => {
                        if (wrap.classList.toggle('seleccionado')) {
                            wrap.innerHTML = makeSVG(COLOR_SELECTED);

                            const inputId = document.createElement('input');
                            inputId.type = 'hidden';
                            inputId.name = 'asientos_seleccionados[]';
                            inputId.value = asiento.id;
                            asientoInputContainer.appendChild(inputId);

                            const inputZona = document.createElement('input');
                            inputZona.type = 'hidden';
                            inputZona.name = `asientos_zonas[${asiento.id}]`;
                            inputZona.value = zona.idZona;
                            asientoInputContainer.appendChild(inputZona);
                        } else {
                            wrap.innerHTML = makeSVG(COLOR_FREE);

                            document.querySelector(`input[name='asientos_seleccionados[]'][value='${asiento.id}']`)?.remove();
                            document.querySelector(`input[name='asientos_zonas[${asiento.id}]']`)?.remove();
                        }
                    });

                    mapaAsientos.appendChild(wrap);
                });
            });
        })
        .catch(() => {
            zonasContent.innerHTML = '<p class="text-red-600 text-sm">Error al cargar zonas.</p>';
        });
});
</script>

</x-layout.nav>