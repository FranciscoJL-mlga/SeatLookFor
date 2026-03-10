<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
    @foreach($establecimientos as $establecimiento)
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden transform transition hover:-translate-y-2 hover:shadow-2xl">
            <img src="{{ $establecimiento->imagen }}" alt="{{ $establecimiento->nombre }}" class="w-full h-48 object-cover">
            <div class="p-4">
                <h2 class="text-xl font-semibold mb-2 text-gray-800">{{ $establecimiento->nombre }}</h2>
                <p class="text-gray-600">{{ $establecimiento->ubicacion }}</p>
            </div>
        </div>
    @endforeach
</div>

<!-- Paginación -->
<div class="mt-10 flex justify-center">
    {{ $establecimientos->links('pagination::tailwind') }}
</div>
