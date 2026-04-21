<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entrada - {{ $evento->titulo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            background: #0d0d14;
            color: #f8fafc;
            padding: 24px;
        }

        /* ── Header ── */
        .header {
            background: #13131f;
            border: 1px solid rgba(139,92,246,0.3);
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .brand {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #8b5cf6;
            margin-bottom: 6px;
        }

        .event-title {
            font-size: 22px;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 14px;
            line-height: 1.2;
        }

        .header-grid {
            width: 100%;
        }

        .header-grid td {
            vertical-align: top;
            padding-right: 24px;
        }

        .info-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 13px;
            font-weight: 600;
            color: #f8fafc;
        }

        .accent { color: #f59e0b; }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid rgba(139,92,246,0.2);
            margin: 16px 0;
        }

        /* ── Ticket stub ── */
        .ticket {
            background: #13131f;
            border: 1px solid rgba(139,92,246,0.25);
            border-radius: 12px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .ticket-header {
            background: #8b5cf6;
            padding: 10px 20px;
        }

        .ticket-header-table {
            width: 100%;
        }

        .ticket-number {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #fff;
        }

        .ticket-id {
            font-size: 10px;
            color: rgba(255,255,255,0.7);
            text-align: right;
        }

        .ticket-body {
            padding: 18px 20px;
        }

        .ticket-info-table {
            width: 100%;
        }

        .ticket-info-table td {
            vertical-align: top;
            padding-bottom: 12px;
        }

        .ticket-col-left  { width: 30%; }
        .ticket-col-mid   { width: 30%; }
        .ticket-col-right { width: 40%; text-align: right; }

        /* ── Seat badge ── */
        .seat-badge {
            display: inline-block;
            background: rgba(139,92,246,0.15);
            border: 1px solid rgba(139,92,246,0.4);
            border-radius: 6px;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 700;
            color: #8b5cf6;
            letter-spacing: 0.5px;
        }

        /* ── Price tag ── */
        .price-tag {
            font-size: 22px;
            font-weight: 700;
            color: #f59e0b;
        }

        /* ── Perforated separator ── */
        .perforation {
            border-top: 2px dashed rgba(139,92,246,0.25);
            margin: 0 20px;
        }

        .ticket-footer {
            padding: 10px 20px;
            background: #1c1c2e;
        }

        .ticket-footer-table {
            width: 100%;
        }

        .footer-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-value {
            font-size: 11px;
            color: #94a3b8;
        }

        /* ── Document footer ── */
        .doc-footer {
            margin-top: 24px;
            text-align: center;
            color: #64748b;
            font-size: 9px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .doc-footer-line {
            border-top: 1px solid rgba(139,92,246,0.15);
            padding-top: 12px;
        }
    </style>
</head>
<body>

    {{-- ── Header del evento ── --}}
    <div class="header">
        <div class="brand">SeatLookFor</div>
        <div class="event-title">{{ $evento->titulo }}</div>
        <hr class="divider">
        <table class="header-grid">
            <tr>
                <td>
                    <div class="info-label">Establecimiento</div>
                    <div class="info-value">{{ $establecimiento->nombre }}</div>
                </td>
                <td>
                    <div class="info-label">Ubicación</div>
                    <div class="info-value">{{ $establecimiento->ubicacion }}</div>
                </td>
                <td>
                    <div class="info-label">Fecha</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</div>
                </td>
                <td>
                    <div class="info-label">Hora</div>
                    <div class="info-value accent">{{ \Carbon\Carbon::parse($evento->fecha)->format('H:i') }}</div>
                </td>
                <td>
                    <div class="info-label">Titular</div>
                    <div class="info-value">{{ $usuario->nombre }} {{ $usuario->apellido }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ── Stubs individuales ── --}}
    @foreach ($reservas as $index => $reserva)
    @php $asiento = $asientos[$index] ?? null; @endphp
    <div class="ticket">

        {{-- Cabecera violeta --}}
        <div class="ticket-header">
            <table class="ticket-header-table">
                <tr>
                    <td class="ticket-number">Entrada #{{ $loop->iteration }}</td>
                    <td class="ticket-id">Ref. #{{ $reserva->idRes }}</td>
                </tr>
            </table>
        </div>

        {{-- Cuerpo --}}
        <div class="ticket-body">
            <table class="ticket-info-table">
                <tr>
                    <td class="ticket-col-left">
                        <div class="info-label">Zona</div>
                        <div style="font-size:18px;font-weight:700;color:#f8fafc;margin-top:2px;">
                            {{ $asiento->zona ?? '—' }}
                        </div>
                    </td>
                    <td class="ticket-col-mid">
                        <div class="info-label">Asiento</div>
                        <div style="margin-top:4px;">
                            <span class="seat-badge">
                                Fila&nbsp;{{ $asiento->ejeY ?? '?' }}&nbsp;&nbsp;Col&nbsp;{{ $asiento->ejeX ?? '?' }}
                            </span>
                        </div>
                    </td>
                    <td class="ticket-col-right">
                        <div class="info-label" style="text-align:right;">Precio</div>
                        <div class="price-tag">{{ number_format($reserva->totalPrecio, 2) }}&nbsp;€</div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Línea perforada --}}
        <div class="perforation"></div>

        {{-- Pie del stub --}}
        <div class="ticket-footer">
            <table class="ticket-footer-table">
                <tr>
                    <td>
                        <div class="footer-label">Reservado el</div>
                        <div class="footer-value">{{ \Carbon\Carbon::parse($reserva->fechaReserva)->format('d/m/Y') }}</div>
                    </td>
                    <td style="text-align:right;">
                        <div class="footer-label">Evento</div>
                        <div class="footer-value">{{ $evento->titulo }}</div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
    @endforeach

    {{-- ── Pie de documento ── --}}
    <div class="doc-footer">
        <div class="doc-footer-line">
            SeatLookFor &mdash; Reserva de entradas &mdash; {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

</body>
</html>
