<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entradas</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .ticket { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; }
        h2 { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>Entradas - {{ $evento->titulo }}</h1>
    <p><strong>Usuario:</strong> {{ $usuario->nombre }} {{ $usuario->apellido }}</p>
    <p><strong>Establecimiento:</strong> {{ $establecimiento->nombre }}</p>
    <p><strong>Ubicación:</strong> {{ $establecimiento->ubicacion }}</p>
    <p><strong>Fecha del evento:</strong> {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y H:i') }}</p>
    <hr>

    @foreach ($reservas as $index => $reserva)
        <div class="ticket">
            <h2>Entrada #{{ $reserva->idRes }}</h2>
            <p><strong>Asiento:</strong>
                {{ $asientos[$index]->zona ?? 'Zona desconocida' }},
                Fila {{ $asientos[$index]->ejeY ?? '?' }},
                Columna {{ $asientos[$index]->ejeX ?? '?' }}
            </p>
            <p><strong>Precio:</strong> ${{ number_format($reserva->totalPrecio, 2) }}</p>
            <p><strong>Fecha de reserva:</strong> {{ \Carbon\Carbon::parse($reserva->fechaReserva)->format('d/m/Y H:i') }}</p>
        </div>
    @endforeach
</body>
</html>
