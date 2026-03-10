<x-layout.nav>

<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Crear Evento</h1>

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

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}" class="w-full border rounded" required>
            </div>
            <div>
                <label for="duracion">Duracion</label>
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
            <div class="overflow-x-auto">
                <div id="mapa-asientos" class="relative bg-gray-100 border rounded-xl p-4 mx-auto" style="width: 1000px; height: 600px;"></div>
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

    fetch(`/zonas-por-establecimiento/${idEst}`)
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
                    const div = document.createElement('div');
                    div.className = "absolute text-xs text-white flex items-center justify-center rounded cursor-pointer font-bold";
                    div.style.width = "40px";
                    div.style.height = "40px";
                    div.style.left = `${asiento.ejeX * 50 + 5}px`;
                    div.style.top = `${asiento.ejeY * 50 + 5}px`;
                    div.style.backgroundColor = "#22c55e";
                    div.innerText = `Z-${asiento.zona}`;
                    div.dataset.id = asiento.id;

                    div.addEventListener('click', () => {
                        const selector = `input[name='asientos_seleccionados[]'][value='${asiento.id}']`;

                        if (div.classList.toggle('seleccionado')) {
                            div.style.backgroundColor = "#3b82f6"; // azul

                            const inputId = document.createElement('input');
                            inputId.type = 'hidden';
                            inputId.name = 'asientos_seleccionados[]';
                            inputId.value = asiento.id;
                            inputId.dataset.id = asiento.id;
                            asientoInputContainer.appendChild(inputId);

                            const inputZona = document.createElement('input');
                            inputZona.type = 'hidden';
                            inputZona.name = `asientos_zonas[${asiento.id}]`;
                            inputZona.value = zona.idZona;
                            asientoInputContainer.appendChild(inputZona);
                        } else {
                            div.style.backgroundColor = "#22c55e"; // verde

                            const input1 = document.querySelector(`input[name='asientos_seleccionados[]'][value='${asiento.id}']`);
                            const input2 = document.querySelector(`input[name='asientos_zonas[${asiento.id}]']`);
                            if (input1) input1.remove();
                            if (input2) input2.remove();
                        }
                    });

                    mapaAsientos.appendChild(div);
                });
            });
        })
        .catch(() => {
            zonasContent.innerHTML = '<p class="text-red-600 text-sm">Error al cargar zonas.</p>';
        });
});
</script>

</x-layout.nav>