<!-- Crear Establecimiento -->
<x-layout.nav>

    
    @section('content')
    <div class="container mx-auto py-10 px-4">
        <h1 class="text-4xl font-bold text-center mb-10 text-gray-800">Crear Establecimiento</h1>
        
        <form action="{{ route('establecimientos.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-8">
            @csrf
            <div class="mb-6">
                <label for="nombre" class="block text-gray-700 font-semibold mb-2">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="w-full border-gray-300 rounded-lg p-3" required>
            </div>

            <div class="mb-6">
            <label for="ubicacion" class="block text-gray-700 font-semibold mb-2">Ubicación:</label>
            <input type="text" name="ubicacion" id="ubicacion" class="w-full border-gray-300 rounded-lg p-3" required>
        </div>

        <div class="mb-6">
            <label for="imagen" class="block text-gray-700 font-semibold mb-2">Imagen:</label>
            <input type="file" name="imagen" id="imagen" class="w-full border-gray-300 rounded-lg p-3">
        </div>
        
        <button type="submit" class="bg-blue-600 text-white rounded-lg px-6 py-3 mt-4">Crear Establecimiento</button>
    </form>
</div>
@endsection

<!-- Editar Establecimiento -->


@section('content')
<div class="container mx-auto py-10 px-4">
    <h1 class="text-4xl font-bold text-center mb-10 text-gray-800">Editar Establecimiento</h1>
    
    <form action="{{ route('establecimientos.update', $establecimiento->idEst) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-8">
        @csrf
        @method('PUT')
        <div class="mb-6">
            <label for="nombre" class="block text-gray-700 font-semibold mb-2">Nombre:</label>
            <input type="text" name="nombre" id="nombre" class="w-full border-gray-300 rounded-lg p-3" value="{{ $establecimiento->nombre }}" required>
        </div>
        
        <div class="mb-6">
            <label for="ubicacion" class="block text-gray-700 font-semibold mb-2">Ubicación:</label>
            <input type="text" name="ubicacion" id="ubicacion" class="w-full border-gray-300 rounded-lg p-3" value="{{ $establecimiento->ubicacion }}" required>
        </div>
        
        <div class="mb-6">
            <label for="imagen" class="block text-gray-700 font-semibold mb-2">Imagen:</label>
            <input type="file" name="imagen" id="imagen" class="w-full border-gray-300 rounded-lg p-3">
            @if($establecimiento->imagen)
            <img src="{{ asset($establecimiento->imagen) }}" alt="Imagen actual" class="mt-4 w-48 rounded-lg">
            @endif
        </div>
        
        <button type="submit" class="bg-green-600 text-white rounded-lg px-6 py-3 mt-4">Actualizar Establecimiento</button>
    </form>
</div>
@endsection

</x-layout.nav>