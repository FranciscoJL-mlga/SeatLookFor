<x-layout.nav>
    <div class="max-w-7xl mx-auto py-10 px-4">
        <!-- ENCABEZADO -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $establecimiento->nombre }}</h1>
            <p class="text-lg text-gray-500">{{ $establecimiento->ubicacion }}</p>
            @if($establecimiento->tipo)
                <p class="text-sm text-gray-400 italic">{{ $establecimiento->tipo }}</p>
            @endif
        </div>

        <!-- IMAGEN -->
        @if($establecimiento->imagen)
            <div class="mb-10 flex justify-center">
                <img src="{{ asset($establecimiento->imagen) }}" alt="Imagen del establecimiento"
                     class="rounded-lg shadow-lg w-full max-w-2xl h-64 object-cover">
            </div>
        @endif

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
            $uniqueZones = $establecimiento->asientos->pluck('zona')->unique()->values();
            $zoneColors  = [];
            foreach ($uniqueZones as $i => $zona) {
                $zoneColors[$zona] = $zonePalette[$i % count($zonePalette)];
            }
        @endphp

        <!-- LEYENDA -->
        <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-600 mb-6">
            @foreach($zoneColors as $zona => $color)
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded inline-block" style="background-color:{{ $color }}"></span>
                Zona {{ $zona }}
            </div>
            @endforeach
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded bg-gray-400 inline-block"></span> Ocupado
            </div>
        </div>

        <!-- MAPA DE ASIENTOS -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Mapa de Asientos</h2>

        <div id="estmap-wrap" style="width:100%;overflow:hidden;">
            <div id="estmap-inner" class="relative bg-white border rounded-xl shadow p-4" style="width:1000px;height:600px;transform-origin:top left;">
                @foreach($establecimiento->asientos as $asiento)
                    <div
                        class="absolute text-[11px] font-semibold text-white flex items-center justify-center rounded-md shadow-sm"
                        style="
                            width: 40px;
                            height: 40px;
                            left: {{ $asiento->ejeX * 50 + 5 }}px;
                            top: {{ $asiento->ejeY * 50 + 5 }}px;
                            background-color: {{ $asiento->estado === 'ocupado' ? '#9ca3af' : ($zoneColors[$asiento->zona] ?? '#22c55e') }};
                        "
                        title="Zona: {{ $asiento->zona }} | Precio: €{{ number_format($asiento->precio, 2) }}"
                    >
                        {{ $asiento->zona }}
                    </div>
                @endforeach
            </div>
        </div>
        @if($establecimiento->eventos->count() > 0)
            <div class="bg-yellow-100 text-yellow-800 border-l-4 border-yellow-500 p-4 mb-6 rounded">
                Este establecimiento tiene {{ $establecimiento->eventos->count() }} evento(s) asociado(s) y no puede eliminarse hasta que se eliminen.
            </div>
        @endif

        @if($establecimiento->eventos->count() > 0)
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Eventos en este establecimiento</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($establecimiento->eventos as $evento)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        @if($evento->portada)
                            <img src="{{ asset($evento->portada) }}" alt="{{ $evento->titulo }}" class="w-full h-40 object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $evento->titulo }}</h3>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y H:i') }}</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded-full
                                @if($evento->estado === 'activo') bg-green-100 text-green-800
                                @elseif($evento->estado === 'finalizado') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($evento->estado) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif



        @if($establecimiento->eventos->count() === 0)
            <form action="{{ route('establecimiento.eliminar', $establecimiento->idEst) }}" method="POST" onsubmit="return confirm('¿Eliminar este establecimiento y todos sus asientos?');">
                @csrf
                
                <button type="submit"
                    class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition font-semibold mt-4">
                    Eliminar establecimiento
                </button>
            </form>
        @endif


        <!-- VOLVER O ACCIONES -->
        <div class="mt-12 text-center">
            <a href="{{ route('establecimiento.listado') }}"
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                ← Volver al listado
            </a>
        </div>
    </div>
<script>
(function () {
    var wrap  = document.getElementById('estmap-wrap');
    var inner = document.getElementById('estmap-inner');
    function fit() {
        var scale = Math.min(1, wrap.offsetWidth / 1000);
        inner.style.transform = 'scale(' + scale + ')';
        wrap.style.height = Math.ceil(600 * scale) + 'px';
    }
    fit();
    window.addEventListener('resize', fit);
})();
</script>
</x-layout.nav>
