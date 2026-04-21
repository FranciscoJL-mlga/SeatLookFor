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

        <!-- MAPA DE ASIENTOS -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Mapa de Asientos</h2>

            {{-- Leyenda --}}
            <div style="display:flex;gap:20px;margin-bottom:14px;flex-wrap:wrap;">
                @php
                    $zonaColores = ['A' => '#8b5cf6', 'B' => '#3b82f6', 'C' => '#10b981'];
                    $coloresExtra = ['#f59e0b','#ef4444','#ec4899','#06b6d4','#84cc16'];
                    $colIdx = 0;
                @endphp
                @foreach($evento->asientos->groupBy('zona') as $zona => $seats)
                    @php $color = $zonaColores[$zona] ?? $coloresExtra[$colIdx++ % count($coloresExtra)]; @endphp
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
                    {{-- Escenario --}}
                    <div style="position:absolute;top:8px;left:50%;transform:translateX(-50%);background:#1e293b;color:#fff;font-size:12px;font-weight:700;letter-spacing:2px;padding:6px 40px;border-radius:6px;">ESCENARIO</div>

                    @php $colIdx = 0; @endphp
                    @foreach($evento->asientos as $asiento)
                        @php
                            $color = $zonaColores[$asiento->zona] ?? $coloresExtra[$colIdx++ % count($coloresExtra)];
                        @endphp
                        <div title="Zona {{ $asiento->zona }} — Fila {{ $asiento->ejeY }}, Col {{ $asiento->ejeX }}"
                             style="position:absolute;
                                    left:{{ $asiento->ejeX * 50 + 5 }}px;
                                    top:{{ $asiento->ejeY * 50 + 20 }}px;
                                    width:46px;height:50px;">
                            <svg viewBox="0 0 44 48" width="44" height="48" xmlns="http://www.w3.org/2000/svg">
                                <rect x="5" y="0" width="34" height="29" rx="7" fill="{{ $color }}"/>
                                <rect x="0" y="32" width="44" height="14" rx="5" fill="{{ $color }}"/>
                            </svg>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

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
</x-layout.nav>
