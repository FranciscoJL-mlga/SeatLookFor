<x-layout.nav>

<div style="margin-bottom:32px;">
    <h1 style="font-family:'Poppins',sans-serif;font-size:1.8rem;font-weight:700;color:var(--text);">Dashboard</h1>
    <p style="color:var(--text-dim);font-size:0.875rem;margin-top:4px;">Resumen general de la plataforma.</p>
</div>

<!-- Tarjetas de estadísticas -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:32px;">

    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
        <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-dim);margin-bottom:8px;">Establecimientos</div>
        <div style="font-family:'Poppins',sans-serif;font-size:2.2rem;font-weight:700;color:var(--primary);">{{ $totalEstablecimientos ?? 0 }}</div>
        <div style="font-size:0.8rem;color:var(--text-dim);margin-top:4px;">registrados en total</div>
    </div>

    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
        <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-dim);margin-bottom:8px;">Total Eventos</div>
        <div style="font-family:'Poppins',sans-serif;font-size:2.2rem;font-weight:700;color:var(--accent);">{{ $totalEventos ?? 0 }}</div>
        <div style="font-size:0.8rem;color:var(--text-dim);margin-top:4px;">creados en la plataforma</div>
    </div>

    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
        <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-dim);margin-bottom:8px;">Eventos Activos</div>
        <div style="font-family:'Poppins',sans-serif;font-size:2.2rem;font-weight:700;color:#22c55e;">{{ $eventosActivos ?? 0 }}</div>
        <div style="font-size:0.8rem;color:var(--text-dim);margin-top:4px;">disponibles ahora</div>
    </div>

    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
        <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-dim);margin-bottom:8px;">Total Reservas</div>
        <div style="font-family:'Poppins',sans-serif;font-size:2.2rem;font-weight:700;color:#f97316;">{{ $totalReservas ?? 0 }}</div>
        <div style="font-size:0.8rem;color:var(--text-dim);margin-top:4px;">realizadas por usuarios</div>
    </div>

</div>

<!-- Acciones rápidas -->
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;margin-bottom:28px;">
    <h2 style="font-family:'Poppins',sans-serif;font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--primary);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--border);">
        Acciones Rápidas
    </h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
        <a href="{{ route('establecimientos.crear') }}"
           style="display:block;padding:16px 20px;background:var(--bg-raised);border:1px solid var(--border);border-radius:10px;font-size:0.875rem;font-weight:600;color:var(--text);transition:border-color 0.2s,box-shadow 0.2s;"
           onmouseover="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 1px var(--primary)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
            + Nuevo Establecimiento
        </a>
        <a href="{{ route('eventos.crear') }}"
           style="display:block;padding:16px 20px;background:var(--bg-raised);border:1px solid var(--border);border-radius:10px;font-size:0.875rem;font-weight:600;color:var(--text);transition:border-color 0.2s,box-shadow 0.2s;"
           onmouseover="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 1px var(--primary)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
            + Nuevo Evento
        </a>
        <a href="{{ route('establecimiento.listado') }}"
           style="display:block;padding:16px 20px;background:var(--bg-raised);border:1px solid var(--border);border-radius:10px;font-size:0.875rem;font-weight:600;color:var(--text);transition:border-color 0.2s,box-shadow 0.2s;"
           onmouseover="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 1px var(--primary)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
            Ver Establecimientos
        </a>
        <a href="{{ route('eventos.listado') }}"
           style="display:block;padding:16px 20px;background:var(--bg-raised);border:1px solid var(--border);border-radius:10px;font-size:0.875rem;font-weight:600;color:var(--text);transition:border-color 0.2s,box-shadow 0.2s;"
           onmouseover="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 1px var(--primary)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
            Ver Eventos
        </a>
    </div>
</div>

<!-- Últimos eventos -->
<div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;">
    <h2 style="font-family:'Poppins',sans-serif;font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--primary);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--border);">
        Últimos Eventos
    </h2>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
            <thead>
                <tr style="border-bottom:1px solid var(--border);">
                    <th style="text-align:left;padding:10px 14px;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-dim);">Evento</th>
                    <th style="text-align:left;padding:10px 14px;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-dim);">Establecimiento</th>
                    <th style="text-align:left;padding:10px 14px;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-dim);">Fecha</th>
                    <th style="text-align:left;padding:10px 14px;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-dim);">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimosEventos ?? [] as $evento)
                    <tr style="border-bottom:1px solid var(--border-subtle);transition:background 0.15s;"
                        onmouseover="this.style.background='var(--bg-raised)'"
                        onmouseout="this.style.background='transparent'">
                        <td style="padding:12px 14px;color:var(--text);font-weight:500;">{{ $evento->titulo }}</td>
                        <td style="padding:12px 14px;color:var(--text-muted);">{{ $evento->establecimiento->nombre ?? '—' }}</td>
                        <td style="padding:12px 14px;color:var(--text-muted);">{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</td>
                        <td style="padding:12px 14px;">
                            @if($evento->estado === 'activo')
                                <span style="background:rgba(34,197,94,0.15);color:#86efac;border:1px solid rgba(34,197,94,0.25);padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Activo</span>
                            @elseif($evento->estado === 'cancelado')
                                <span style="background:rgba(239,68,68,0.15);color:#fca5a5;border:1px solid rgba(239,68,68,0.25);padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">Cancelado</span>
                            @else
                                <span style="background:var(--bg-raised);color:var(--text-dim);border:1px solid var(--border);padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">{{ ucfirst($evento->estado) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:32px;text-align:center;color:var(--text-dim);font-style:italic;">
                            No hay eventos recientes
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</x-layout.nav>
