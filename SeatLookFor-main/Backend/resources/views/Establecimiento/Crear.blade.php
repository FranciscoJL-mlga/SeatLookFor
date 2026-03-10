<x-layout.nav>
    <style>
        .butaca {
            width: 40px;
            height: 40px;
            background-color: #22c55e;
            border-radius: 6px;
            color: white;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            cursor: grab;
            user-select: none;
        }

        .grid-cell {
            width: 50px;
            height: 50px;
            border: 1px dashed #ddd;
            box-sizing: border-box;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, 50px);
            grid-auto-rows: 50px;
            position: relative;
        }

        .escenario {
            width: 300px;
            height: 40px;
            background-color: #1e293b;
            color: white;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            border-radius: 6px;
            cursor: move;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">



<div class="max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center">Editor de Asientos con Escenario</h1>

    <form id="formularioEstablecimiento" method="POST" action="{{ route('establecimiento.guardar') }}">
        @csrf

        <div class="mb-6">
            <h2 class="text-2xl font-bold mb-4">Crear Establecimiento</h2>


                @if($errors->any())
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium">Nombre</label>
                    <input type="text" name="nombre" required class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Ubicación</label>
                    <input type="text" name="ubicacion" required class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Imagen (URL)</label>
                    <input type="text" name="imagen" required class="w-full border rounded p-2">
                </div>
            </div>
        </div>

        <input type="hidden" name="asientos" id="inputAsientos">

        <div class="mb-4 flex flex-wrap items-center gap-4">
            <label class="text-lg font-medium">Nombre de la Zona:</label>
            <input type="text" id="zona" maxlength="5" placeholder="Máx. 5 caracteres" class="p-2 border rounded w-40">

            <label class="text-lg font-medium">Modo:</label>
            <select id="modo" class="border p-2 rounded">
                <option value="add">Añadir asientos</option>
                <option value="move">Mover</option>
            </select>

            <button id="deshacer"
                    type="button"
                    class="ml-auto px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                Deshacer (Ctrl+Z)
            </button>
        </div>

        <div id="canvas"
             class="relative w-[1000px] h-[600px] bg-white border rounded shadow overflow-hidden grid-container"
             style="grid-template-columns: repeat(20, 50px);">
        </div>

        <div class="mt-6 text-center">
            <button type="submit"
                    class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 text-lg">
                Guardar mapa
            </button>
        </div>
    </form>
</div>
<script>
    const canvas = document.getElementById('canvas');
    const zonaInput = document.getElementById('zona');
    const modoInput = document.getElementById('modo');
    const deshacerBtn = document.getElementById('deshacer');

    let modo = 'add';
    let seatCount = 1;
    let escenarioEl = null;
    const history = [];

    const filas = 12;
    const columnas = 20;

    for (let i = 0; i < filas * columnas; i++) {
        const cell = document.createElement('div');
        cell.classList.add('grid-cell');
        canvas.appendChild(cell);
    }

    modoInput.addEventListener('change', (e) => {
        modo = e.target.value;
    });

    canvas.addEventListener('click', (e) => {
        const rect = canvas.getBoundingClientRect();
        const x = Math.floor((e.clientX - rect.left) / 50) * 50 + 5;
        const y = Math.floor((e.clientY - rect.top) / 50) * 50 + 5;

        if (modo === 'add') {
            const zona = zonaInput.value.trim();
            if (!zona) return alert("Primero escribe una zona");

            const codigo = `${zona}-${seatCount}`;

            const div = document.createElement('div');
            div.className = 'butaca';
            div.innerText = codigo;
            div.style.left = `${x}px`;
            div.style.top = `${y}px`;
            div.dataset.codigo = codigo;
            div.dataset.zona = zona;
            div.dataset.x = x/50;
            div.dataset.y = y/50;

            makeDraggable(div);
            canvas.appendChild(div);
            history.push({ tipo: 'add', element: div });
            seatCount++;
        } else if (modo === 'stage') {
            if (!escenarioEl) {
                escenarioEl = document.createElement('div');
                escenarioEl.className = 'escenario';
                escenarioEl.innerText = 'ESCENARIO';
                escenarioEl.style.left = `${x}px`;
                escenarioEl.style.top = `${y}px`;
                canvas.appendChild(escenarioEl);
                makeStageDraggable(escenarioEl);
            } else {
                escenarioEl.style.left = `${x}px`;
                escenarioEl.style.top = `${y}px`;
            }
        }
    });

    function makeDraggable(el) {
        let isDragging = false;
        let offsetX, offsetY;
        let originalX, originalY;

        el.addEventListener('mousedown', (e) => {
            if (modo !== 'move') return;
            isDragging = true;
            offsetX = e.offsetX;
            offsetY = e.offsetY;
            originalX = parseInt(el.style.left);
            originalY = parseInt(el.style.top);
            el.style.zIndex = 1000;
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const rect = canvas.getBoundingClientRect();
            let x = Math.round((e.clientX - rect.left - offsetX) / 50) * 50 + 5;
            let y = Math.round((e.clientY - rect.top - offsetY) / 50) * 50 + 5;
            el.style.left = `${x}px`;
            el.style.top = `${y}px`;
            el.dataset.x = x/50;
            el.dataset.y = y/50;
        });

        document.addEventListener('mouseup', () => {
            if (isDragging) {
                const newX = parseInt(el.style.left);
                const newY = parseInt(el.style.top);
                if (originalX !== newX || originalY !== newY) {
                    history.push({
                        tipo: 'move',
                        element: el,
                        oldX: originalX/50,
                        oldY: originalY/50
                    });
                }
            }
            isDragging = false;
            el.style.zIndex = '';
        });
    }

    function makeStageDraggable(el) {
        let isDragging = false;
        let offsetX, offsetY;

        el.addEventListener('mousedown', (e) => {
            if (modo !== 'stage') return;
            isDragging = true;
            offsetX = e.offsetX;
            offsetY = e.offsetY;
            el.style.zIndex = 1000;
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging || modo !== 'stage') return;
            const rect = canvas.getBoundingClientRect();
            const x = Math.round((e.clientX - rect.left - offsetX) / 50) * 50 + 5;
            const y = Math.round((e.clientY - rect.top - offsetY) / 50) * 50 + 5;

            el.style.left = `${x}px`;
            el.style.top = `${y}px`;
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            el.style.zIndex = '';
        });
    }

    function deshacerUltimaAccion() {
        const last = history.pop();
        if (!last) return;

        if (last.tipo === 'add') {
            last.element.remove();
        } else if (last.tipo === 'move') {
            const el = last.element;
            el.style.left = `${last.oldX}px`;
            el.style.top = `${last.oldY}px`;
            el.dataset.x = last.oldX;
            el.dataset.y = last.oldY;
        }
    }

    deshacerBtn.addEventListener('click', () => deshacerUltimaAccion());
    document.addEventListener('keydown', (e) => {
        if (e.ctrlKey && e.key === 'z') {
            e.preventDefault();
            deshacerUltimaAccion();
        }
    });

    // ✅ GUARDAR MAPA: convierte los asientos en JSON antes del envío del formulario
    document.getElementById('formularioEstablecimiento').addEventListener('submit', function () {
        const resultado = [];

        document.querySelectorAll('.butaca').forEach(el => {
            resultado.push({
                estado: 'libre',
                zona: el.dataset.zona,
                ejeX: parseInt(el.dataset.x),
                ejeY: parseInt(el.dataset.y),
                precio: 0.00
            });
        });

        if (escenarioEl) {
            resultado.push({
                estado: 'ocupado',
                zona: 'escenario',
                ejeX: parseInt(escenarioEl.style.left),
                ejeY: parseInt(escenarioEl.style.top),
                precio: 0.00
            });
        }

        document.getElementById('inputAsientos').value = JSON.stringify(resultado);
    });
</script>

</x-layout.nav>
