<x-layout.nav>
    <div class="max-w-7xl mx-auto py-10 px-4">
        <!-- ENCABEZADO -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $evento->titulo }}</h1>
            <p class="text-lg text-gray-500">{{ $evento->establecimiento->nombre }}</p>
            <p class="text-sm text-gray-400">{{ $evento->fecha }}</p>
        </div>

        <!-- IMAGEN DEL ESTABLECIMIENTO -->
        @if($evento->establecimiento->imagen)
            <div class="mb-10 flex justify-center">
                <img src="{{ $evento->establecimiento->imagen }}" alt="Imagen del establecimiento"
                     class="rounded-lg shadow-lg w-full max-w-2xl h-64 object-cover">
            </div>
        @endif

        <!-- DETALLES DEL EVENTO -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Detalles del Evento</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 mb-2"><span class="font-semibold">Descripción:</span></p>
                    <p class="text-gray-800">{{ $evento->descripcion }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-2"><span class="font-semibold">Ubicación:</span></p>
                    <p class="text-gray-800">{{ $evento->establecimiento->ubicacion }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-2"><span class="font-semibold">Estado:</span></p>
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full 
                        @if($evento->estado === 'activo') bg-green-100 text-green-800
                        @elseif($evento->estado === 'cancelado') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($evento->estado) }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-600 mb-2"><span class="font-semibold">Valoración:</span></p>
                    <p class="text-gray-800">{{ $evento->valoracion ?? 'Sin valoración' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-2"><span class="font-semibold">Reservas realizadas:</span></p>
                    <p class="text-gray-800">{{ $evento->ReservaDeEventos->count() }}</p>
                </div>
            </div>
        </div>

        @php
            $zonePalette = [
                '#3b82f6','#10b981','#8b5cf6','#06b6d4',
                '#f43f5e','#84cc16','#d946ef','#f97316',
                '#14b8a6','#6366f1','#ec4899','#eab308',
                '#22c55e','#ef4444','#fb923c','#38bdf8',
                '#a3e635','#2dd4bf','#c026d3','#d97706',
                '#1d4ed8','#15803d','#7c3aed','#be123c',
                '#0891b2','#65a30d','#9333ea','#0284c7',
                '#f472b6','#4ade80','#fb7185','#34d399',
            ];
            $uniqueZones = $evento->asientos->pluck('zona')->unique()->values();
            $zoneColorMap = [];
            foreach ($uniqueZones as $i => $zona) {
                $zoneColorMap[$zona] = $zonePalette[$i % count($zonePalette)];
            }
        @endphp

        <!-- MAPA DE ASIENTOS -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Mapa de Asientos</h2>

            {{-- Leyenda --}}
            <div style="display:flex;gap:20px;margin-bottom:14px;flex-wrap:wrap;">
                @foreach($zoneColorMap as $zona => $color)
                    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#374151;">
                        <svg viewBox="0 0 44 48" width="18" height="20" xmlns="http://www.w3.org/2000/svg">
                            <rect x="5" y="0" width="34" height="29" rx="7" fill="{{ $color }}"/>
                            <rect x="0" y="32" width="44" height="14" rx="5" fill="{{ $color }}"/>
                        </svg>
                        Zona {{ $zona }}
                    </div>
                @endforeach
            </div>

            <div class="overflow-x-auto">
                <div class="relative border rounded-xl bg-gray-50 p-4 mx-auto" style="width:1000px;height:600px;">
                    <div style="position:absolute;top:8px;left:50%;transform:translateX(-50%);background:#1e293b;color:#fff;font-size:12px;font-weight:700;letter-spacing:2px;padding:6px 40px;border-radius:6px;">ESCENARIO</div>

                    @foreach($evento->asientos as $asiento)
                        @php $color = $zoneColorMap[$asiento->zona] ?? '#94a3b8'; @endphp
                        <div title="Zona {{ $asiento->zona }} — Fila {{ $asiento->ejeY }}, Col {{ $asiento->ejeX }} | Precio: {{ $asiento->pivot->precio }}€"
                             style="position:absolute;left:{{ $asiento->ejeX * 50 + 5 }}px;top:{{ $asiento->ejeY * 50 + 20 }}px;width:46px;height:50px;">
                            <svg viewBox="0 0 44 48" width="44" height="48" xmlns="http://www.w3.org/2000/svg">
                                <rect x="5" y="0" width="34" height="29" rx="7" fill="{{ $color }}"/>
                                <rect x="0" y="32" width="44" height="14" rx="5" fill="{{ $color }}"/>
                            </svg>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- PRECIOS POR ZONA -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-1">Precios por Zona</h2>
            <p class="text-sm text-gray-500 mb-5">Cambia el precio de todos los asientos de una zona a la vez.</p>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('eventos.precio-zona', $evento->idEve) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-5">
                    @foreach($evento->asientos->groupBy('zona') as $zona => $asientosZona)
                        @php
                            $precioActual = $asientosZona->first()->pivot->precio ?? 0;
                            $colorZona    = $zoneColorMap[$zona] ?? '#94a3b8';
                        @endphp
                        <div class="border rounded-lg p-4" style="border-left:4px solid {{ $colorZona }};">
                            <div class="flex items-center gap-2 mb-3">
                                <svg viewBox="0 0 44 48" width="16" height="18" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="5" y="0" width="34" height="29" rx="7" fill="{{ $colorZona }}"/>
                                    <rect x="0" y="32" width="44" height="14" rx="5" fill="{{ $colorZona }}"/>
                                </svg>
                                <span class="font-semibold text-gray-800">Zona {{ $zona }}</span>
                                <span class="text-xs text-gray-400 ml-auto">{{ $asientosZona->count() }} asientos</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="number"
                                       name="precios[{{ $zona }}]"
                                       value="{{ $precioActual }}"
                                       min="0" step="0.01"
                                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <span class="text-gray-500 text-sm">€</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Guardar precios
                </button>
            </form>
        </div>

        <!-- HABILITAR MÁS ASIENTOS -->
        @if($asientosDisponibles->isNotEmpty())
        @php
            $dispZones = $asientosDisponibles->pluck('zona')->unique()->values();
            $dispColorMap = [];
            foreach ($dispZones as $zona) {
                // reutilizar el color si la zona ya existe en el evento, si no asignar uno nuevo
                if (isset($zoneColorMap[$zona])) {
                    $dispColorMap[$zona] = $zoneColorMap[$zona];
                } else {
                    $allZones = array_merge(array_keys($zoneColorMap), array_keys($dispColorMap));
                    $idx = count($allZones);
                    $dispColorMap[$zona] = $zonePalette[$idx % count($zonePalette)];
                }
            }
        @endphp
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-1">Habilitar más asientos</h2>
            <p class="text-sm text-gray-500 mb-5">
                Selecciona asientos del establecimiento que todavía no están en este evento y asígnales un precio.
            </p>

            <form action="{{ route('eventos.vincular-asientos', $evento->idEve) }}" method="POST">
                @csrf
                @foreach($asientosDisponibles->groupBy('zona') as $zona => $asientosZona)
                    @php $colorZona = $dispColorMap[$zona] ?? '#94a3b8'; @endphp
                    <div class="border rounded-lg p-4 mb-4" style="border-left:4px solid {{ $colorZona }};">
                        <div class="flex flex-wrap items-center gap-4 mb-3">
                            <div class="flex items-center gap-2">
                                <svg viewBox="0 0 44 48" width="16" height="18" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="5" y="0" width="34" height="29" rx="7" fill="{{ $colorZona }}"/>
                                    <rect x="0" y="32" width="44" height="14" rx="5" fill="{{ $colorZona }}"/>
                                </svg>
                                <span class="font-semibold text-gray-800">Zona {{ $zona }}</span>
                                <span class="text-xs text-gray-400">({{ $asientosZona->count() }} disponibles)</span>
                            </div>
                            <div class="flex items-center gap-2 ml-auto">
                                <label class="text-sm text-gray-600">Precio zona:</label>
                                <input type="number"
                                       name="precio_zona[{{ $zona }}]"
                                       placeholder="0.00"
                                       min="0" step="0.01"
                                       class="w-28 border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <span class="text-gray-500 text-sm">€</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <label class="flex items-center gap-1 text-sm text-blue-600 cursor-pointer font-medium mr-2"
                                   onclick="toggleZone('zona-{{ $zona }}', this)">
                                <input type="checkbox" class="hidden"> Seleccionar todos
                            </label>
                            @foreach($asientosZona as $asiento)
                                <label class="zona-{{ $zona }} flex items-center gap-1 border rounded px-2 py-1 text-xs cursor-pointer hover:bg-gray-50 transition"
                                       style="border-color:{{ $colorZona }}20;">
                                    <input type="checkbox" name="asientos[]" value="{{ $asiento->idAsi }}" class="accent-blue-600">
                                    <span class="text-gray-700">F{{ $asiento->ejeY }}·C{{ $asiento->ejeX }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Habilitar asientos seleccionados
                </button>
            </form>
        </div>
        @endif

        <!-- CAMBIO DE ESTADO -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Cambiar Estado del Evento</h2>
            <form action="{{ route('eventos.estado', $evento->idEve) }}" method="POST" class="flex items-center gap-4">
                @csrf
                <select name="estado" class="border rounded px-3 py-2 text-gray-700">
                    <option value="activo" {{ $evento->estado === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="finalizado" {{ $evento->estado === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                </select>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                    Actualizar Estado
                </button>
            </form>
        </div>

        <!-- ELIMINAR EVENTO -->
        <form action="{{ route('eventos.eliminar', $evento->idEve) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento? Esta acción no se puede deshacer.');">
            @csrf
            <button type="submit" class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition font-semibold">
                Eliminar evento
            </button>
        </form>

        <!-- BOTONES DE ACCIÓN -->
        <div class="mt-12 flex justify-center space-x-4">
            <a href="{{ route('eventos.listado') }}"
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                ← Volver al listado
            </a>
        </div>
    </div>
<script>
function toggleZone(cls, btn) {
    var checks = document.querySelectorAll('.' + cls + ' input[type=checkbox]');
    var allChecked = Array.from(checks).every(c => c.checked);
    checks.forEach(c => c.checked = !allChecked);
    btn.previousElementSibling.checked = !allChecked;
}
</script>
</x-layout.nav>
