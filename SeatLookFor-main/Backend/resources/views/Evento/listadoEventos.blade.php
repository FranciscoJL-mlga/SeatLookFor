<x-layout.nav>
    <div class="container mx-auto py-10 px-4">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Nuestros Eventos</h1>
            <a href="{{ route('eventos.crear') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Crear Evento
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($eventos as $evento)
                <a href="{{ route('eventos.ver', $evento->idEve) }}" class="block group">
                    <div class="bg-white shadow-md hover:shadow-xl transition rounded-xl overflow-hidden">
                        @if($evento->portada)
                            <img src="{{ $evento->portada }}" alt="{{ $evento->titulo }}" class="object-cover w-full h-48 group-hover:scale-105 transition-transform">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">Sin imagen</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-1">{{ $evento->titulo }}</h2>
                            <p class="text-sm text-gray-500">{{ $evento->fecha }}</p>
                            <p class="text-sm text-gray-500">{{ $evento->establecimiento->nombre }}</p>
                            <div class="mt-2">
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($evento->estado === 'activo') bg-green-100 text-green-800
                                    @elseif($evento->estado === 'cancelado') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($evento->estado) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-10 flex justify-center">
            {{ $eventos->links() }}
        </div>
    </div>
</x-layout.nav> 