@extends('web.layouts.app')

@section('title', 'Mi Perfil - SeatLookFor')

@section('content')

<div class="vista" x-data="{ seccion: 'perfil' }">
    <div class="contenido">

        {{-- ── Left sidebar ── --}}
        <div class="panel-izquierdo">

            {{-- Avatar placeholder --}}
            <div style="padding:20px 24px 24px;border-bottom:1px solid var(--border);">
                <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                        <path d="M12 4a4 4 0 0 1 4 4 4 4 0 0 1-4 4 4 4 0 0 1-4-4 4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4z"/>
                    </svg>
                </div>
                <p style="font-family:'Poppins',sans-serif;font-size:14px;font-weight:700;color:var(--text);">
                    {{ $usuario->nombre }} {{ $usuario->apellido }}
                </p>
                <p style="font-size:12px;color:var(--text-dim);margin-top:2px;">{{ $usuario->email }}</p>
            </div>

            <ul>
                <li @click="seccion = 'perfil'"
                    :class="{ active: seccion === 'perfil' }">
                    <span style="display:flex;align-items:center;gap:10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Mi Perfil
                    </span>
                </li>
                <li @click="seccion = 'eventos'"
                    :class="{ active: seccion === 'eventos' }">
                    <span style="display:flex;align-items:center;gap:10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                        Mis Eventos
                    </span>
                </li>
                <li @click="seccion = 'visitados'"
                    :class="{ active: seccion === 'visitados' }">
                    <span style="display:flex;align-items:center;gap:10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        Eventos Visitados
                        @if($eventosVisitados->isNotEmpty())
                            <span style="background:var(--primary);color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:99px;">{{ $eventosVisitados->count() }}</span>
                        @endif
                    </span>
                </li>
                <li @click="seccion = 'configuracion'"
                    :class="{ active: seccion === 'configuracion' }">
                    <span style="display:flex;align-items:center;gap:10px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.07 4.93a10 10 0 0 1 1.44 15.14M4.93 4.93A10 10 0 0 0 3.49 20.07"/>
                        </svg>
                        Configuración
                    </span>
                </li>
            </ul>
        </div>

        {{-- ── Main content ── --}}
        <div class="contenido-principal">

            {{-- ─ Perfil section ─ --}}
            <div x-show="seccion === 'perfil'">
                <h2>Mi Perfil</h2>

                <div style="display:grid;gap:16px;max-width:480px;">
                    <div style="padding:16px 20px;background:var(--bg-raised);border:1px solid var(--border);border-radius:10px;">
                        <p style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-dim);margin-bottom:4px;">Nombre</p>
                        <p style="font-size:16px;font-weight:600;color:var(--text);">{{ $usuario->nombre }}</p>
                    </div>
                    <div style="padding:16px 20px;background:var(--bg-raised);border:1px solid var(--border);border-radius:10px;">
                        <p style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-dim);margin-bottom:4px;">Apellido</p>
                        <p style="font-size:16px;font-weight:600;color:var(--text);">{{ $usuario->apellido }}</p>
                    </div>
                    <div style="padding:16px 20px;background:var(--bg-raised);border:1px solid var(--border);border-radius:10px;">
                        <p style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text-dim);margin-bottom:4px;">Email</p>
                        <p style="font-size:16px;font-weight:600;color:var(--text);">{{ $usuario->email }}</p>
                    </div>
                </div>

                <div style="margin-top:28px;">
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="submit-button"
                                style="max-width:200px;background:linear-gradient(135deg,#ef4444,#dc2626);">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

            {{-- ─ Mis Eventos section ─ --}}
            <div x-show="seccion === 'eventos'">
                <h2>Mis Eventos</h2>

                @if($reservas->isEmpty())
                    <div style="padding:48px 24px;text-align:center;color:var(--text-dim);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                             stroke-linejoin="round" style="margin:0 auto 16px;display:block;opacity:0.4;">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                        <p style="font-size:15px;">Aún no tienes reservas realizadas.</p>
                        <a href="{{ route('eventos.index') }}"
                           style="display:inline-block;margin-top:16px;padding:10px 24px;background:var(--primary);color:#fff;border-radius:8px;font-weight:600;font-size:14px;transition:opacity 0.2s;"
                           onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                            Explorar eventos
                        </a>
                    </div>
                @else
                    <div style="display:flex;flex-direction:column;gap:14px;">
                        @foreach($reservas as $reserva)
                        <div class="event__comment"
                             style="display:flex;gap:16px;align-items:center;padding:16px 20px;">
                            @if($reserva->evento?->portada)
                                <img src="{{ $reserva->evento->portada }}"
                                     alt="{{ $reserva->evento->titulo }}"
                                     style="width:72px;height:72px;object-fit:cover;border-radius:8px;flex-shrink:0;border:1px solid var(--border);">
                            @else
                                <div style="width:72px;height:72px;border-radius:8px;background:linear-gradient(135deg,#4c1d95,#1e1b4b);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="rgba(139,92,246,0.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                    </svg>
                                </div>
                            @endif
                            <div style="flex:1;min-width:0;">
                                <p style="font-family:'Poppins',sans-serif;font-weight:700;color:var(--text);font-size:15px;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $reserva->evento?->titulo ?? 'Evento eliminado' }}
                                </p>
                                <p style="font-size:12px;color:var(--text-dim);margin-bottom:4px;">
                                    Reservado el {{ \Carbon\Carbon::parse($reserva->fechaReserva)->format('d/m/Y') }}
                                </p>
                                @if($reserva->asiento)
                                    <p style="font-size:12px;color:var(--text-muted);">
                                        Zona {{ $reserva->asiento->zona }} — Fila {{ $reserva->asiento->ejeY }}, Col {{ $reserva->asiento->ejeX }}
                                    </p>
                                @endif
                            </div>
                            <div style="text-align:right;flex-shrink:0;">
                                <p style="font-family:'Poppins',sans-serif;font-size:16px;font-weight:700;color:var(--accent);">
                                    {{ number_format($reserva->totalPrecio, 2) }} €
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ─ Eventos Visitados section ─ --}}
            <div x-show="seccion === 'visitados'">
                <h2>Eventos Visitados Recientemente</h2>

                @if($eventosVisitados->isEmpty())
                    <div style="padding:48px 24px;text-align:center;color:var(--text-dim);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                             stroke-linejoin="round" style="margin:0 auto 16px;display:block;opacity:0.4;">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <p style="font-size:15px;">No tienes eventos finalizados recientes.</p>
                        <p style="font-size:13px;margin-top:8px;color:var(--text-dim);">Los eventos a los que asistas aparecerán aquí durante el mes siguiente.</p>
                    </div>
                @else
                    <p style="font-size:13px;color:var(--text-dim);margin-bottom:20px;">
                        Eventos a los que asististe en el último mes. Puedes comentar en tu asiento hasta 30 días después del evento.
                    </p>
                    <div style="display:flex;flex-direction:column;gap:14px;">
                        @foreach($eventosVisitados as $evento)
                        @php
                            $diasRestantes = max(0, \Carbon\Carbon::parse($evento->fecha)->addMonth()->diffInDays(now(), false) * -1);
                            $puedeComentarAun = $diasRestantes > 0;
                        @endphp
                        <div style="display:flex;gap:16px;align-items:center;padding:16px 20px;background:var(--bg-raised);border:1px solid var(--border);border-radius:12px;">
                            <div style="width:72px;height:72px;border-radius:8px;background:linear-gradient(135deg,#4c1d95,#1e1b4b);flex-shrink:0;display:flex;align-items:center;justify-content:center;border:1px solid var(--border);">
                                @if($evento->imagen)
                                    <img src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->titulo }}"
                                         style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="rgba(139,92,246,0.6)" stroke-width="1.5">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                    </svg>
                                @endif
                            </div>
                            <div style="flex:1;min-width:0;">
                                <p style="font-family:'Poppins',sans-serif;font-weight:700;color:var(--text);font-size:15px;margin-bottom:4px;">
                                    {{ $evento->titulo }}
                                </p>
                                <p style="font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                                    📅 {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                                    · {{ $evento->establecimiento->nombre ?? '' }}
                                </p>
                                @if($puedeComentarAun)
                                    <span style="font-size:11px;color:#86efac;background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.3);padding:2px 8px;border-radius:99px;">
                                        {{ $diasRestantes }}d restantes para comentar
                                    </span>
                                @else
                                    <span style="font-size:11px;color:var(--text-dim);background:var(--bg-card);border:1px solid var(--border);padding:2px 8px;border-radius:99px;">
                                        Plazo expirado
                                    </span>
                                @endif
                            </div>
                            @if($puedeComentarAun)
                            <a href="{{ route('evento.show', base64_encode($evento->idEve)) }}"
                               style="flex-shrink:0;padding:8px 16px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;white-space:nowrap;">
                                Comentar asiento
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ─ Configuración section ─ --}}
            <div x-show="seccion === 'configuracion'"
                 x-init="@if(session('seccion') === 'configuracion') seccion = 'configuracion' @endif">
                <h2>Configuración</h2>

                @if(session('password_success'))
                    <div style="background:rgba(34,197,94,0.15);border:1px solid #22c55e;color:#86efac;padding:10px 16px;border-radius:8px;margin-bottom:20px;font-size:.875rem;">
                        {{ session('password_success') }}
                    </div>
                @endif

                {{-- Cambiar contraseña --}}
                <div style="background:var(--bg-raised);border:1px solid var(--border);border-radius:12px;padding:24px;max-width:480px;margin-bottom:24px;">
                    <h3 style="font-size:1rem;font-weight:700;color:var(--text);margin-bottom:18px;">Cambiar contraseña</h3>

                    @if($errors->has('password_actual'))
                        <div style="background:rgba(239,68,68,0.12);border:1px solid #ef4444;color:#fca5a5;padding:8px 12px;border-radius:8px;margin-bottom:12px;font-size:.8rem;">
                            {{ $errors->first('password_actual') }}
                        </div>
                    @endif
                    @if($errors->has('password_nuevo'))
                        <div style="background:rgba(239,68,68,0.12);border:1px solid #ef4444;color:#fca5a5;padding:8px 12px;border-radius:8px;margin-bottom:12px;font-size:.8rem;">
                            {{ $errors->first('password_nuevo') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('usuario.password') }}">
                        @csrf
                        <div style="margin-bottom:14px;">
                            <label style="display:block;font-size:.8rem;color:var(--text-dim);margin-bottom:5px;">Contraseña actual</label>
                            <input type="password" name="password_actual" required
                                   style="width:100%;background:#1a1a2e;border:1px solid var(--border);color:var(--text-bright);border-radius:8px;padding:9px 12px;font-size:.875rem;box-sizing:border-box;">
                        </div>
                        <div style="margin-bottom:14px;">
                            <label style="display:block;font-size:.8rem;color:var(--text-dim);margin-bottom:5px;">Nueva contraseña</label>
                            <input type="password" name="password_nuevo" required
                                   style="width:100%;background:#1a1a2e;border:1px solid var(--border);color:var(--text-bright);border-radius:8px;padding:9px 12px;font-size:.875rem;box-sizing:border-box;">
                        </div>
                        <div style="margin-bottom:18px;">
                            <label style="display:block;font-size:.8rem;color:var(--text-dim);margin-bottom:5px;">Confirmar nueva contraseña</label>
                            <input type="password" name="password_nuevo_confirmation" required
                                   style="width:100%;background:#1a1a2e;border:1px solid var(--border);color:var(--text-bright);border-radius:8px;padding:9px 12px;font-size:.875rem;box-sizing:border-box;">
                        </div>
                        <button type="submit"
                                style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;padding:10px 24px;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.875rem;">
                            Actualizar contraseña
                        </button>
                    </form>
                </div>

                {{-- Eliminar cuenta --}}
                <div x-data="{ confirmar: false }"
                     style="background:rgba(239,68,68,0.05);border:1px solid rgba(239,68,68,0.3);border-radius:12px;padding:24px;max-width:480px;">
                    <h3 style="font-size:1rem;font-weight:700;color:#f87171;margin-bottom:8px;">Eliminar cuenta</h3>
                    <p style="font-size:.8rem;color:var(--text-dim);margin-bottom:16px;line-height:1.6;">
                        Esta acción es irreversible. Se eliminarán todos tus datos, reservas e historial.
                    </p>

                    @if($errors->has('password_confirm'))
                        <div style="background:rgba(239,68,68,0.12);border:1px solid #ef4444;color:#fca5a5;padding:8px 12px;border-radius:8px;margin-bottom:12px;font-size:.8rem;">
                            {{ $errors->first('password_confirm') }}
                        </div>
                    @endif

                    <div x-show="!confirmar">
                        <button @click="confirmar = true"
                                style="background:transparent;border:1px solid #ef4444;color:#f87171;padding:9px 20px;border-radius:8px;font-weight:600;cursor:pointer;font-size:.875rem;">
                            Eliminar mi cuenta
                        </button>
                    </div>

                    <div x-show="confirmar" x-cloak>
                        <p style="font-size:.8rem;color:#fca5a5;margin-bottom:12px;font-weight:600;">
                            Introduce tu contraseña para confirmar:
                        </p>
                        <form method="POST" action="{{ route('usuario.eliminar') }}">
                            @csrf
                            <input type="password" name="password_confirm" required
                                   placeholder="Tu contraseña"
                                   style="width:100%;background:#1a1a2e;border:1px solid #ef4444;color:var(--text-bright);border-radius:8px;padding:9px 12px;font-size:.875rem;box-sizing:border-box;margin-bottom:12px;">
                            <div style="display:flex;gap:10px;">
                                <button type="submit"
                                        style="background:#dc2626;color:#fff;padding:9px 20px;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.875rem;">
                                    Confirmar eliminación
                                </button>
                                <button type="button" @click="confirmar = false"
                                        style="background:transparent;border:1px solid var(--border);color:var(--text-dim);padding:9px 16px;border-radius:8px;cursor:pointer;font-size:.875rem;">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>{{-- /.contenido-principal --}}
    </div>{{-- /.contenido --}}
</div>{{-- /.vista --}}

@endsection
