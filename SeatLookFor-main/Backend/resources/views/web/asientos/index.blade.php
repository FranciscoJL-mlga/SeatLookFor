@extends('web.layouts.app')

@section('title', 'Seleccionar asiento - ' . $evento->titulo)

@push('styles')
<style>
    .seat-btn { transition: all .15s; }
    .seat-btn:hover:not([disabled]) { transform: scale(1.15); }
</style>
@endpush

@section('content')

<script>
    window.__asientosData = {!! json_encode($asientos) !!};
</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
     x-data="seatSelector(window.__asientosData)">

    <div class="mb-6">
        <a href="{{ route('evento.show', $evento->idEve) }}" class="text-gray-400 hover:text-white transition text-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver al evento
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">

        <!-- Mapa de asientos -->
        <div class="xl:col-span-3">
            <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800">
                <h1 class="text-2xl font-extrabold mb-2">{{ $evento->titulo }}</h1>
                <p class="text-gray-400 text-sm mb-6">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ \Carbon\Carbon::parse($evento->fecha)->format('d \d\e F \d\e Y, H:i') }}
                </p>

                <!-- Leyenda -->
                <div class="flex gap-6 mb-8 text-sm flex-wrap">
                    <span class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded bg-green-600 block"></span> Libre
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded bg-yellow-400 block"></span> Seleccionado
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded bg-gray-700 block"></span> Ocupado
                    </span>
                </div>

                <!-- Escenario -->
                <div class="bg-gray-800 text-center py-3 rounded-xl text-gray-400 text-sm font-semibold mb-8 tracking-widest uppercase">
                    — Escenario —
                </div>

                <!-- Grid de asientos por zona -->
                <template x-for="zona in zonas" :key="zona">
                    <div class="mb-8">
                        <h3 class="text-sm font-bold text-purple-400 mb-3 uppercase tracking-widest" x-text="'Zona ' + zona"></h3>
                        <div class="overflow-x-auto">
                            <div class="inline-block">
                                <template x-for="fila in getFilas(zona)" :key="fila">
                                    <div class="flex items-center gap-1 mb-1">
                                        <span class="text-xs text-gray-600 w-6 text-right mr-2" x-text="fila"></span>
                                        <template x-for="asiento in getAsientosPorFilaYZona(zona, fila)" :key="asiento.idAsi">
                                            <button
                                                type="button"
                                                class="seat-btn w-8 h-8 rounded text-xs font-bold relative group"
                                                :class="{
                                                    'bg-gray-700 cursor-not-allowed text-gray-500': asiento.estado === 'ocupado',
                                                    'bg-green-700 hover:bg-green-600 text-white cursor-pointer': asiento.estado === 'libre' && !isSelected(asiento.idAsi),
                                                    'bg-yellow-400 text-purple-900 ring-2 ring-yellow-300': isSelected(asiento.idAsi)
                                                }"
                                                :disabled="asiento.estado === 'ocupado'"
                                                @click="toggleSeat(asiento)"
                                                :title="'Zona ' + asiento.zona + ' | Fila ' + asiento.ejeY + ', Col ' + asiento.ejeX + ' | ' + asiento.precio + '€'"
                                                x-text="asiento.ejeX">
                                            </button>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Comentarios del asiento seleccionado -->
                <div x-show="hoveredSeat && hoveredSeat.comentarios.length > 0"
                     class="mt-6 bg-gray-800 rounded-xl p-5">
                    <h4 class="font-bold text-sm mb-3 text-yellow-400">
                        Opiniones sobre este asiento
                    </h4>
                    <div class="space-y-3 max-h-48 overflow-y-auto">
                        <template x-for="(c, i) in (hoveredSeat ? hoveredSeat.comentarios : [])" :key="i">
                            <div class="flex gap-3">
                                <template x-if="c.foto">
                                    <img :src="'/' + c.foto" class="w-12 h-12 rounded object-cover flex-shrink-0">
                                </template>
                                <div>
                                    <p class="text-xs font-semibold" x-text="c.nombre + ' ' + c.apellido"></p>
                                    <p class="text-xs text-gray-400" x-text="c.opinion"></p>
                                    <span class="text-yellow-400 text-xs">⭐ <span x-text="c.valoracion"></span></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral: selección -->
        <div class="xl:col-span-1">
            <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 sticky top-20">
                <h2 class="font-bold text-lg mb-4">Tu selección</h2>

                <div x-show="selected.length === 0" class="text-gray-500 text-sm text-center py-8">
                    <i class="bi bi-cursor text-3xl block mb-2"></i>
                    Haz clic en un asiento libre para seleccionarlo
                </div>

                <div x-show="selected.length > 0" class="space-y-2 mb-4 max-h-64 overflow-y-auto">
                    <template x-for="s in selected" :key="s.idAsi">
                        <div class="flex items-center justify-between bg-gray-800 rounded-lg px-3 py-2 text-sm">
                            <div>
                                <span class="font-semibold" x-text="'Zona ' + s.zona"></span>
                                <span class="text-gray-400 text-xs block" x-text="'Fila ' + s.ejeY + ', Col ' + s.ejeX"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-yellow-400 font-bold text-xs" x-text="s.precio + '€'"></span>
                                <button @click="toggleSeat(s)" class="text-gray-500 hover:text-red-400 transition">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="selected.length > 0">
                    <hr class="border-gray-700 mb-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-400">Asientos</span>
                        <span x-text="selected.length"></span>
                    </div>
                    <div class="flex justify-between font-bold text-lg mb-5">
                        <span>Total</span>
                        <span class="text-yellow-400" x-text="total.toFixed(2) + ' €'"></span>
                    </div>

                    <form method="POST" action="{{ route('asientos.seleccionar') }}">
                        @csrf
                        <input type="hidden" name="idEvento" value="{{ $evento->idEve }}">
                        <template x-for="s in selected" :key="s.idAsi">
                            <input type="hidden" name="idAsientos[]" :value="s.idAsi">
                        </template>
                        <button type="submit"
                                class="w-full bg-yellow-400 text-purple-900 font-bold py-3 rounded-xl hover:bg-yellow-300 transition">
                            Continuar <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Formulario comentario -->
            @if(isset($evento->idEve))
            <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 mt-4" x-show="selected.length > 0">
                <h3 class="font-bold text-sm mb-4 text-purple-400">
                    <i class="bi bi-chat-square-text me-1"></i> Comentar asiento seleccionado
                </h3>
                <form method="POST"
                      :action="'/asientos/' + (selected.length ? selected[0].idAsi : '') + '/comentar'"
                      enctype="multipart/form-data"
                      class="space-y-3">
                    @csrf
                    <input type="hidden" name="idEve" value="{{ $evento->idEve }}">

                    <textarea name="opinion" rows="2" required
                              placeholder="Tu opinión sobre la visibilidad..."
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-600 resize-none"></textarea>

                    <div>
                        <label class="text-xs text-gray-400 block mb-1">Valoración (0-5)</label>
                        <input type="number" name="valoracion" min="0" max="5" step="0.5" required
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-600">
                    </div>

                    <div x-data="{ preview: null }">
                        <label class="text-xs text-gray-400 block mb-1">Foto (opcional)</label>
                        <input type="file" name="foto_file" accept="image/*"
                               @change="handleFoto($event)"
                               class="w-full text-xs text-gray-400 file:bg-gray-700 file:border-0 file:text-white file:text-xs file:px-3 file:py-1.5 file:rounded file:cursor-pointer">
                        <input type="hidden" name="foto" x-ref="fotoInput">
                    </div>

                    <button type="submit"
                            class="w-full bg-purple-700 hover:bg-purple-600 text-white text-sm font-semibold py-2 rounded-lg transition">
                        Publicar comentario
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>
</div>

@push('scripts')
<script>
function seatSelector(asientosData) {
    return {
        asientos: asientosData,
        selected: [],
        hoveredSeat: null,

        get zonas() {
            return [...new Set(this.asientos.map(a => a.zona))];
        },

        get total() {
            return this.selected.reduce((sum, s) => sum + parseFloat(s.precio || 0), 0);
        },

        getFilas(zona) {
            const filas = this.asientos
                .filter(a => a.zona === zona)
                .map(a => parseInt(a.ejeY));
            return [...new Set(filas)].sort((a, b) => a - b);
        },

        getAsientosPorFilaYZona(zona, fila) {
            return this.asientos
                .filter(a => a.zona === zona && parseInt(a.ejeY) === fila)
                .sort((a, b) => parseInt(a.ejeX) - parseInt(b.ejeX));
        },

        isSelected(idAsi) {
            return this.selected.some(s => s.idAsi === idAsi);
        },

        toggleSeat(asiento) {
            if (asiento.estado === 'ocupado') return;
            const idx = this.selected.findIndex(s => s.idAsi === asiento.idAsi);
            if (idx >= 0) {
                this.selected.splice(idx, 1);
            } else {
                this.selected.push(asiento);
                this.hoveredSeat = asiento;
            }
        },

        handleFoto(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.$refs.fotoInput.value = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    };
}
</script>
@endpush

@endsection
