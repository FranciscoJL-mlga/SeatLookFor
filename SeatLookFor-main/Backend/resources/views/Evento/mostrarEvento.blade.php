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
                <img src="{{ asset($evento->establecimiento->imagen) }}" alt="Imagen del establecimiento"
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
            $uniqueZones = collect($todosAsientos)->pluck('zona')->unique()->values();
            $zoneColorMap = [];
            foreach ($uniqueZones as $i => $zona) {
                $zoneColorMap[$zona] = $zonePalette[$i % count($zonePalette)];
            }
            $zonaPrecioMap = [];
            foreach (collect($todosAsientos)->groupBy('zona') as $zona => $seats) {
                $enabled = collect($seats)->firstWhere('habilitado', true);
                $zonaPrecioMap[$zona] = $enabled ? $enabled['precio'] : 0;
            }
            $allEjeX = collect($todosAsientos)->pluck('ejeX');
            $allEjeY = collect($todosAsientos)->pluck('ejeY');
            $minX    = $allEjeX->min() ?? 0;
            $minY    = $allEjeY->min() ?? 0;
            $mapW    = (($allEjeX->max() ?? 0) - $minX + 2) * 50 + 20;
            $mapH    = (($allEjeY->max() ?? 0) - $minY + 2) * 50 + 40;
        @endphp

        <!-- GESTIÓN DE ASIENTOS -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-1">Gestión de Asientos</h2>
            <p class="text-sm text-gray-500 mb-5">
                Haz clic en un asiento para habilitarlo o deshabilitarlo. Los asientos con reserva no pueden deshabilitarse.
            </p>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <form action="{{ route('eventos.guardar-asientos', $evento->idEve) }}" method="POST">
                @csrf

                {{-- Precios por zona --}}
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-700 mb-3 text-sm uppercase tracking-wide">Precios por zona</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($zoneColorMap as $zona => $color)
                        <div class="border rounded-lg p-3" style="border-left:4px solid {{ $color }};">
                            <span class="font-semibold text-gray-800 text-sm block mb-2">Zona {{ $zona }}</span>
                            <div class="flex items-center gap-1">
                                <input type="number" name="precio_zona[{{ $zona }}]"
                                       value="{{ $zonaPrecioMap[$zona] ?? 0 }}"
                                       min="0" step="0.01"
                                       class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <span class="text-gray-500 text-xs">€</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Checkboxes ocultos --}}
                @foreach($todosAsientos as $asiento)
                    <input type="checkbox" id="chk-{{ $asiento['idAsi'] }}" name="asientos[]"
                           value="{{ $asiento['idAsi'] }}" style="display:none;"
                           {{ $asiento['habilitado'] ? 'checked' : '' }}>
                @endforeach

                {{-- Leyenda --}}
                <div style="display:flex;gap:20px;margin-bottom:12px;flex-wrap:wrap;align-items:center;">
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#f1f5f9;">
                        <svg viewBox="0 0 44 48" width="16" height="18"><rect x="5" y="0" width="34" height="29" rx="7" fill="#3b82f6"/><rect x="0" y="32" width="44" height="14" rx="5" fill="#3b82f6"/></svg>
                        Habilitado
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#f1f5f9;">
                        <svg viewBox="0 0 44 48" width="16" height="18" style="opacity:0.3"><rect x="5" y="0" width="34" height="29" rx="7" fill="#94a3b8"/><rect x="0" y="32" width="44" height="14" rx="5" fill="#94a3b8"/></svg>
                        Deshabilitado
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#f1f5f9;">
                        <svg viewBox="0 0 44 48" width="16" height="18"><rect x="5" y="0" width="34" height="29" rx="7" fill="#f97316"/><rect x="0" y="32" width="44" height="14" rx="5" fill="#f97316"/></svg>
                        🔒 Con reserva (no modificable)
                    </div>
                </div>

                {{-- Mapa de asientos --}}
                <div id="seatmap-wrap" style="width:100%;overflow:hidden;margin-bottom:20px;">
                    <div id="seatmap-inner" style="position:relative;width:{{ $mapW }}px;height:{{ $mapH }}px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;transform-origin:top left;">
                        <div style="position:absolute;top:8px;left:50%;transform:translateX(-50%);background:#1e293b;color:#fff;font-size:11px;font-weight:700;letter-spacing:2px;padding:4px 24px;border-radius:4px;white-space:nowrap;">ESCENARIO</div>

                        @foreach($todosAsientos as $asiento)
                        @php
                            $color    = $zoneColorMap[$asiento['zona']] ?? '#94a3b8';
                            $svgColor = $asiento['habilitado'] ? $color : '#94a3b8';
                            $opacity  = $asiento['habilitado'] ? '1' : '0.3';
                            $cursor   = $asiento['reservado'] ? 'not-allowed' : 'pointer';
                            $left     = ($asiento['ejeX'] - $minX) * 50 + 10;
                            $top      = ($asiento['ejeY'] - $minY) * 50 + 32;
                            $title    = 'Zona '.$asiento['zona'].' · F'.$asiento['ejeY'].'·C'.$asiento['ejeX'];
                            if ($asiento['habilitado']) $title .= ' · '.number_format($asiento['precio'],2).'€';
                            if ($asiento['reservado'])  $title .= ' · Con reserva';
                        @endphp
                        <div id="seat-{{ $asiento['idAsi'] }}"
                             onclick="toggleSeat({{ $asiento['idAsi'] }}, {{ $asiento['reservado'] ? 'true' : 'false' }}, '{{ $color }}')"
                             title="{{ $title }}"
                             style="position:absolute;left:{{ $left }}px;top:{{ $top }}px;width:44px;height:48px;cursor:{{ $cursor }};opacity:{{ $opacity }};transition:opacity .15s;">
                            <svg viewBox="0 0 44 48" width="44" height="48" xmlns="http://www.w3.org/2000/svg">
                                <rect id="r1-{{ $asiento['idAsi'] }}" x="5" y="0" width="34" height="29" rx="7" fill="{{ $svgColor }}"/>
                                <rect id="r2-{{ $asiento['idAsi'] }}" x="0" y="32" width="44" height="14" rx="5" fill="{{ $svgColor }}"/>
                            </svg>
                            @if($asiento['reservado'])
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-60%);font-size:11px;pointer-events:none;line-height:1;">🔒</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Guardar cambios
                </button>
            </form>
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
<script>
(function () {
    var mapW = {{ $mapW }}, mapH = {{ $mapH }};
    var wrap  = document.getElementById('seatmap-wrap');
    var inner = document.getElementById('seatmap-inner');
    function fit() {
        var scale = Math.min(1, wrap.offsetWidth / mapW);
        inner.style.transform = 'scale(' + scale + ')';
        wrap.style.height = Math.ceil(mapH * scale) + 'px';
    }
    fit();
    window.addEventListener('resize', fit);
})();

function toggleSeat(idAsi, reservado, zoneColor) {
    if (reservado) return;
    var chk  = document.getElementById('chk-' + idAsi);
    var seat = document.getElementById('seat-' + idAsi);
    var r1   = document.getElementById('r1-' + idAsi);
    var r2   = document.getElementById('r2-' + idAsi);
    chk.checked = !chk.checked;
    if (chk.checked) {
        r1.setAttribute('fill', zoneColor);
        r2.setAttribute('fill', zoneColor);
        seat.style.opacity = '1';
    } else {
        r1.setAttribute('fill', '#94a3b8');
        r2.setAttribute('fill', '#94a3b8');
        seat.style.opacity = '0.3';
    }
}
</script>
</x-layout.nav>
