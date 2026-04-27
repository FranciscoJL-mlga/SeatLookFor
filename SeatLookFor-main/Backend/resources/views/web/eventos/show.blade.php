@extends('web.layouts.app')

@section('title', $evento->titulo . ' - SeatLookFor')

@section('content')

{{-- Seat data passed to Alpine.js --}}
<script>
    window.__asientosData = {!! json_encode($asientos) !!};
    window.__isAuth = {{ auth()->check() ? 'true' : 'false' }};
    window.__loginUrl = "{{ route('login') }}";
    // Comentar en asiento: solo si el evento está finalizado Y dentro del mes
    window.__puedeCommentarAsiento = {{ ($evento->estado === 'finalizado' && $puedeCommentar) ? 'true' : 'false' }};
    window.__eventoFinalizado = {{ $evento->estado === 'finalizado' ? 'true' : 'false' }};
</script>

<div class="layout" x-data="seatSelector(window.__asientosData)">

    {{-- ═══════════════════════════════════════════════════
         FIXED RESERVATION PANEL (right side)
         ═══════════════════════════════════════════════════ --}}
    <div class="event__reservation">

        <h3>Tu Reserva</h3>

        {{-- Selected seats list --}}
        <div x-show="selected.length > 0">
            <h4>Asientos seleccionados</h4>
            <ul>
                <template x-for="asiento in selected" :key="asiento.idAsi">
                    <li>
                        <span style="flex:1;font-size:12px;line-height:1.5;">
                            Zona <span x-text="asiento.zona"></span> ·
                            Fila <span x-text="asiento.ejeX"></span>,
                            Asiento <span x-text="asiento.ejeY"></span>
                        </span>
                        <span style="font-weight:700;color:var(--accent);font-size:12px;white-space:nowrap;">
                            <span x-text="parseFloat(asiento.precio).toFixed(2)"></span>€
                        </span>
                        <button class="event__btn-eliminar" @click="toggleSeat(asiento)">✕</button>
                    </li>
                </template>
            </ul>
            <p>Total: <span x-text="total.toFixed(2)"></span>€</p>
        </div>

        {{-- Empty state --}}
        <p x-show="selected.length === 0" style="font-size:13px;color:var(--text-dim);padding:8px 0;">
            No has seleccionado ningún asiento todavía.
        </p>

        {{-- Reservation form (auth-gated, hidden for finalized events) --}}
        @if($evento->estado === 'finalizado')
            <div style="padding:14px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.3);border-radius:10px;text-align:center;">
                <p style="font-size:12px;color:#f87171;font-weight:600;margin-bottom:4px;">Evento finalizado</p>
                @if($puedeCommentar)
                    <p style="font-size:11px;color:var(--text-dim);">Puedes comentar en tu asiento hasta {{ \Carbon\Carbon::parse($evento->fecha)->addMonth()->format('d/m/Y') }}.</p>
                @else
                    <p style="font-size:11px;color:var(--text-dim);">El plazo para comentar ha expirado.</p>
                @endif
            </div>
        @else
            @auth
                <form method="POST" action="{{ route('asientos.seleccionar') }}">
                    @csrf
                    <input type="hidden" name="idEvento" value="{{ $evento->idEve }}">
                    <template x-for="s in selected" :key="s.idAsi">
                        <input type="hidden" name="idAsientos[]" :value="s.idAsi">
                    </template>
                    <button type="submit" class="event__button" :disabled="selected.length === 0">
                        Confirmar Reserva
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="event__button"
                   style="display:inline-block;text-align:center;">
                    Inicia sesión para reservar
                </a>
            @endauth
        @endif

        {{-- Seat legend --}}
        <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-dim);margin-bottom:10px;">Leyenda</p>
            <div style="display:flex;flex-direction:column;gap:9px;">
                @foreach($zoneColors as $zona => [$c,$cd])
                <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
                    <svg viewBox="0 0 36 40" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:18px;flex-shrink:0;">
                        <rect x="4" y="2" width="28" height="22" rx="5" fill="{{ $cd }}"/>
                        <rect x="3" y="0" width="28" height="21" rx="5" fill="{{ $c }}"/>
                        <rect x="3" y="0" width="28" height="8"  rx="5" fill="rgba(255,255,255,0.2)"/>
                        <rect x="0" y="13" width="5" height="15" rx="3" fill="{{ $cd }}"/>
                        <rect x="31" y="13" width="5" height="15" rx="3" fill="{{ $cd }}"/>
                        <rect x="4" y="27" width="28" height="13" rx="4" fill="{{ $cd }}"/>
                        <rect x="3" y="26" width="28" height="12" rx="4" fill="{{ $c }}"/>
                        <rect x="3" y="26" width="28" height="5"  rx="3" fill="rgba(255,255,255,0.18)"/>
                    </svg>
                    Zona {{ $zona }}
                </div>
                @endforeach
                @foreach([['#a78bfa','#4c1d95','Tu asiento'],['#f59e0b','#78350f','Seleccionado'],['#f97316','#7c2d12','Bloqueado'],['#dc2626','#7f1d1d','Ocupado']] as [$c,$cd,$label])
                <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
                    <svg viewBox="0 0 36 40" xmlns="http://www.w3.org/2000/svg" style="width:16px;height:18px;flex-shrink:0;{{ $label==='Ocupado'?'opacity:.5;':'' }}">
                        <rect x="4" y="2" width="28" height="22" rx="5" fill="{{ $cd }}"/>
                        <rect x="3" y="0" width="28" height="21" rx="5" fill="{{ $c }}"/>
                        <rect x="3" y="0" width="28" height="8"  rx="5" fill="rgba(255,255,255,0.2)"/>
                        <rect x="0" y="13" width="5" height="15" rx="3" fill="{{ $cd }}"/>
                        <rect x="31" y="13" width="5" height="15" rx="3" fill="{{ $cd }}"/>
                        <rect x="4" y="27" width="28" height="13" rx="4" fill="{{ $cd }}"/>
                        <rect x="3" y="26" width="28" height="12" rx="4" fill="{{ $c }}"/>
                        <rect x="3" y="26" width="28" height="5"  rx="3" fill="rgba(255,255,255,0.18)"/>
                    </svg>
                    {{ $label }}
                </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════
         MAIN EVENT CONTENT
         ═══════════════════════════════════════════════════ --}}
    <div class="event">

        {{-- ── Event title ── --}}
        <div class="event__title">
            <p class="event__title-text">{{ $evento->titulo }}</p>
        </div>

        {{-- ── Seat map ── --}}
        <div class="event__map">

            @php
                $uniqueRows = collect($asientos)->pluck('ejeY')->unique()->sort()->values();
                $uniqueEjeX = collect($asientos)->pluck('ejeX')->unique()->sort()->values();
                $minEjeX    = $uniqueEjeX->first() ?? 3;
                $maxEjeX    = $uniqueEjeX->last()  ?? 14;

                $zonePalette = [
                    ['#3b82f6','#1e3a8a'],
                    ['#10b981','#064e3b'],
                    ['#8b5cf6','#4c1d95'],
                    ['#06b6d4','#164e63'],
                    ['#f43f5e','#881337'],
                    ['#84cc16','#3f6212'],
                    ['#d946ef','#701a75'],
                    ['#f97316','#7c2d12'],
                    ['#14b8a6','#134e4a'],
                    ['#6366f1','#312e81'],
                    ['#ec4899','#831843'],
                    ['#eab308','#713f12'],
                    ['#22c55e','#14532d'],
                    ['#ef4444','#7f1d1d'],
                    ['#fb923c','#9a3412'],
                    ['#38bdf8','#0369a1'],
                    ['#a3e635','#365314'],
                    ['#2dd4bf','#115e59'],
                    ['#c026d3','#701a75'],
                    ['#d97706','#78350f'],
                    ['#1d4ed8','#1e3a8a'],
                    ['#15803d','#064e3b'],
                    ['#7c3aed','#4c1d95'],
                    ['#be123c','#881337'],
                    ['#0891b2','#164e63'],
                    ['#65a30d','#3f6212'],
                    ['#9333ea','#581c87'],
                    ['#0284c7','#0c4a6e'],
                    ['#f472b6','#9d174d'],
                    ['#4ade80','#14532d'],
                    ['#fb7185','#881337'],
                    ['#34d399','#064e3b'],
                ];
                $uniqueZones = collect($asientos)->pluck('zona')->unique()->values();
                $zoneColors  = [];
                foreach ($uniqueZones as $i => $zona) {
                    $zoneColors[$zona] = $zonePalette[$i % count($zonePalette)];
                }
            @endphp

            {{-- Screen --}}
            <div class="cinema-screen-wrap">
                <div class="cinema-screen">ESCENARIO</div>
            </div>
            <div class="cinema-screen-glow"></div>

            @if($asientos->count() > 0)
                {{-- Row labels + seat grid --}}
                <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;width:100%;">
                <div style="display:inline-flex;align-items:flex-start;gap:0;min-width:max-content;">

                    {{-- Row letters --}}
                    <div style="display:grid;grid-template-rows:repeat({{ $uniqueRows->count() }},50px);gap:5px;">
                        @foreach($uniqueRows as $i => $rowNum)
                            <div class="seat-row-label">{{ chr(65 + $i) }}</div>
                        @endforeach
                    </div>

                    {{-- Seats + column numbers --}}
                    <div>
                        <div class="seats">
                            @foreach($asientos as $asiento)
                                <div class="seats__seat
                                            @if($asiento['estado'] === 'libre')    seats__seat--free     @endif
                                            @if($asiento['estado'] === 'ocupado')  seats__seat--occupied @endif
                                            @if($asiento['estado'] === 'bloqueado') seats__seat--locked  @endif
                                            @if($asiento['esPropio'])              seats__seat--mine     @endif"
                                     :class="{ 'seats__seat--selected': isSelected({{ $asiento['idAsi'] }}) }"
                                     style="grid-column:{{ max(1,(int)$asiento['ejeX']-($minEjeX-1)) }};grid-row:{{ $asiento['ejeY'] }};{{ $asiento['estado']==='ocupado'?'cursor:pointer;':'' }}--zone-c:{{ $zoneColors[$asiento['zona']][0] }};--zone-cd:{{ $zoneColors[$asiento['zona']][1] }};"
                                     @if($asiento['esPropio'])
                                         @click="openCommentModal(findSeat({{ $asiento['idAsi'] }}))"
                                     @elseif($asiento['estado'] === 'libre')
                                         @click="toggleSeatById({{ $asiento['idAsi'] }})"
                                     @elseif($asiento['estado'] === 'bloqueado')
                                         {{-- no action, locked by another user --}}
                                     @endif
                                     @mouseenter="showTooltipById($event,{{ $asiento['idAsi'] }})"
                                     @mouseleave="hideTooltip()"
                                     title="Zona {{ $asiento['zona'] }} · Fila {{ chr(64+$asiento['ejeY']) }} · Precio {{ $asiento['precio'] }}€">
                                    <svg viewBox="0 0 36 40" xmlns="http://www.w3.org/2000/svg" style="width:34px;height:38px;overflow:visible;">
                                        <!-- 3D depth shadow -->
                                        <rect x="4" y="2" width="28" height="22" rx="5" style="fill:var(--cd)"/>
                                        <!-- Backrest -->
                                        <rect x="3" y="0" width="28" height="21" rx="5" style="fill:var(--c)"/>
                                        <!-- Backrest top shine -->
                                        <rect x="3" y="0" width="28" height="8" rx="5" fill="rgba(255,255,255,0.22)"/>
                                        <!-- Left armrest -->
                                        <rect x="0" y="13" width="5" height="15" rx="3" style="fill:var(--cd)"/>
                                        <!-- Right armrest -->
                                        <rect x="31" y="13" width="5" height="15" rx="3" style="fill:var(--cd)"/>
                                        <!-- Cushion depth shadow -->
                                        <rect x="4" y="27" width="28" height="13" rx="4" style="fill:var(--cd)"/>
                                        <!-- Cushion -->
                                        <rect x="3" y="26" width="28" height="12" rx="4" style="fill:var(--c)"/>
                                        <!-- Cushion top shine -->
                                        <rect x="3" y="26" width="28" height="5" rx="3" fill="rgba(255,255,255,0.18)"/>
                                    </svg>
                                </div>
                            @endforeach
                        </div>

                        {{-- Column numbers --}}
                        <div style="display:flex;gap:5px;margin-top:6px;">
                            @for($col = $minEjeX; $col <= $maxEjeX; $col++)
                                <div class="seat-col-label">{{ $col }}</div>
                            @endfor
                        </div>
                    </div>
                </div>
                </div>

                {{-- Tooltip carousel --}}
                <div class="seats__tooltip"
                     x-show="tooltip.visible && tooltip.comentarios.length > 0"
                     :style="'left:' + tooltip.x + 'px; top:' + tooltip.y + 'px;'"
                     @mouseenter="tooltip.hovered = true"
                     @mouseleave="tooltip.hovered = false; hideTooltip()">

                    {{-- Navigation header --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                        <button class="seats__nav-button"
                                x-show="tooltip.comentarios.length > 1"
                                @click.stop="tooltip.current = (tooltip.current - 1 + tooltip.comentarios.length) % tooltip.comentarios.length">❮</button>
                        <span style="font-size:11px;color:var(--text-dim);"
                              x-show="tooltip.comentarios.length > 1"
                              x-text="(tooltip.current + 1) + ' / ' + tooltip.comentarios.length"></span>
                        <button class="seats__nav-button"
                                x-show="tooltip.comentarios.length > 1"
                                @click.stop="tooltip.current = (tooltip.current + 1) % tooltip.comentarios.length">❯</button>
                    </div>

                    {{-- Photo --}}
                    <template x-if="tooltip.comentarios[tooltip.current] && tooltip.comentarios[tooltip.current].foto">
                        <img :src="'/' + tooltip.comentarios[tooltip.current].foto"
                             style="width:100%;height:160px;object-fit:cover;border-radius:8px;display:block;margin-bottom:10px;">
                    </template>

                    {{-- User + rating --}}
                    <p style="font-weight:600;font-size:12px;color:var(--text);margin-bottom:2px;"
                       x-text="tooltip.comentarios[tooltip.current] ? tooltip.comentarios[tooltip.current].nombre + ' ' + tooltip.comentarios[tooltip.current].apellido : ''"></p>
                    <p style="font-size:12px;color:var(--accent);margin-bottom:6px;"
                       x-text="tooltip.comentarios[tooltip.current] ? '⭐ ' + tooltip.comentarios[tooltip.current].valoracion : ''"></p>

                    {{-- Opinion text --}}
                    <p style="font-size:12px;color:var(--text-muted);line-height:1.5;max-height:80px;overflow-y:auto;"
                       x-text="tooltip.comentarios[tooltip.current] ? tooltip.comentarios[tooltip.current].opinion : ''"></p>

                    {{-- Dot indicators --}}
                    <div class="seats__indicators" x-show="tooltip.comentarios.length > 1">
                        <template x-for="(c, i) in tooltip.comentarios" :key="i">
                            <span class="seats__indicator"
                                  :class="{ 'seats__indicator--active': i === tooltip.current }"
                                  @click="tooltip.current = i">●</span>
                        </template>
                    </div>
                </div>

            @else
                <div style="text-align:center;color:var(--text-dim);padding:40px 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                         stroke-linejoin="round" style="margin:0 auto 12px;display:block;">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/>
                    </svg>
                    <p>No hay asientos disponibles.</p>
                </div>
            @endif

            {{-- Hint --}}
            @if($evento->estado === 'finalizado' && $puedeCommentar)
                <p style="font-size:11px;color:var(--text-dim);text-align:center;margin-top:12px;opacity:.7;">
                    Haz clic en tu asiento <span style="color:#a78bfa;font-weight:600;">morado</span> para valorarlo
                </p>
            @elseif($evento->estado !== 'finalizado')
                <p style="font-size:11px;color:var(--text-dim);text-align:center;margin-top:12px;opacity:.7;">
                    Podrás valorar tu asiento una vez finalice el evento
                </p>
            @endif
        </div>

        {{-- ── Event info ── --}}
        <div class="event__info">
            @if($evento->establecimiento && $evento->establecimiento->imagen)
            <div class="event__info-image">
                <img src="{{ $evento->establecimiento->imagen }}"
                     alt="Portada de establecimiento">
            </div>
            @endif

            <div class="event__info-name">
                <h3>{{ $evento->establecimiento->nombre ?? '' }}</h3>
                <p class="event__info-location">
                    📍 {{ $evento->establecimiento->ubicacion ?? '' }}
                </p>
            </div>

            <div class="event__info-content">
                <div class="event__info-section">
                    <h4>Detalles del Evento</h4>
                    <p><strong>Título:</strong> {{ $evento->titulo }}</p>
                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</p>
                    <p><strong>Duración:</strong> {{ $evento->duracion }}</p>
                    <p><strong>Categoría:</strong> {{ ucfirst($evento->categoria) }}</p>
                    <p><strong>Tipo:</strong> {{ ucfirst($evento->tipo) }}</p>
                </div>
                <div class="event__info-section">
                    <h4>Sinopsis</h4>
                    <p>{{ $evento->descripcion }}</p>
                </div>
                @if($evento->establecimiento)
                <div class="event__info-section">
                    <h4>Ubicación del Evento</h4>
                    <p>{{ $evento->establecimiento->ubicacion }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Comments ── --}}
        @php
            $comentariosAsiento = $asientos->flatMap(function($a) {
                return collect($a['comentarios'])->map(function($c) use ($a) {
                    $c['zona'] = $a['zona']; $c['ejeX'] = $a['ejeX']; $c['ejeY'] = $a['ejeY'];
                    return $c;
                });
            })->values();
            $todoComentarios = $comentariosEvento->isNotEmpty() || $comentariosAsiento->isNotEmpty();
        @endphp
        <div class="event__comments" x-data="{ replyOpen: null }">
            <h3 class="event__comments-title">Comentarios</h3>

            {{-- ── Formulario comentario de evento ── --}}
            @auth
            <div style="margin-bottom:24px;padding:16px 20px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;">
                <p style="font-size:12px;font-weight:600;color:var(--text-dim);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">Escribe un comentario</p>
                @if(session('success'))
                    <div style="background:rgba(34,197,94,0.15);border:1px solid #22c55e;color:#86efac;padding:8px 12px;border-radius:8px;margin-bottom:10px;font-size:.8rem;">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('eventos.comentar', $evento->idEve) }}">
                    @csrf
                    <textarea name="opinion" rows="3" required
                              style="width:100%;background:#1a1a2e;border:1px solid var(--border);color:var(--text-bright);border-radius:8px;padding:9px 12px;font-size:.875rem;resize:vertical;box-sizing:border-box;"
                              placeholder="¿Qué te pareció el evento?"></textarea>
                    <button type="submit"
                            style="margin-top:10px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;padding:8px 20px;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.85rem;">
                        Publicar
                    </button>
                </form>
            </div>
            @endauth

            @if(!$todoComentarios)
                <p style="color:var(--text-dim);font-style:italic;padding:20px 0;">
                    No hay comentarios todavía. ¡Sé el primero en opinar!
                </p>
            @endif

            {{-- ── Comentarios del evento (con respuestas) ── --}}
            @foreach($comentariosEvento as $com)
            <div class="event__comment" style="margin-bottom:12px;">
                <div class="event__comment-header">
                    <div class="event__comment-user">
                        <div class="event__comment-name">{{ $com->usuario->nombre ?? '' }} {{ $com->usuario->apellido ?? '' }}</div>
                        <div style="font-size:11px;color:var(--text-dim);margin-top:2px;">Comentario del evento</div>
                    </div>
                </div>
                <p style="margin:6px 0 10px;line-height:1.7;color:var(--text);">{{ $com->opinion }}</p>

                {{-- Respuestas --}}
                @foreach($com->respuestas as $resp)
                <div style="margin-left:20px;padding:10px 14px;background:var(--bg-card);border-left:2px solid var(--primary);border-radius:0 8px 8px 0;margin-bottom:6px;">
                    <p style="font-size:12px;font-weight:600;color:var(--accent);margin-bottom:4px;">
                        ↳ {{ $resp->usuario->nombre ?? '' }} {{ $resp->usuario->apellido ?? '' }}
                    </p>
                    <p style="font-size:.875rem;color:var(--text);line-height:1.6;margin:0;">{{ $resp->opinion }}</p>
                </div>
                @endforeach

                {{-- Botón responder --}}
                @auth
                <div x-show="replyOpen !== {{ $com->idCom }}" style="margin-top:6px;">
                    <button @click="replyOpen = {{ $com->idCom }}"
                            style="background:none;border:none;color:var(--text-dim);font-size:12px;cursor:pointer;padding:0;">
                        ↩ Responder
                    </button>
                </div>
                <div x-show="replyOpen === {{ $com->idCom }}" x-cloak style="margin-top:10px;">
                    <form method="POST" action="{{ route('comentarios.responder', $com->idCom) }}">
                        @csrf
                        <textarea name="opinion" rows="2" required
                                  style="width:100%;background:#1a1a2e;border:1px solid var(--border);color:var(--text-bright);border-radius:8px;padding:8px 12px;font-size:.8rem;resize:vertical;box-sizing:border-box;"
                                  placeholder="Escribe tu respuesta..."></textarea>
                        <div style="display:flex;gap:8px;margin-top:6px;">
                            <button type="submit"
                                    style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;padding:6px 16px;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.8rem;">
                                Responder
                            </button>
                            <button type="button" @click="replyOpen = null"
                                    style="background:transparent;border:1px solid var(--border);color:var(--text-dim);padding:6px 12px;border-radius:8px;cursor:pointer;font-size:.8rem;">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
                @endauth
            </div>
            @endforeach

            {{-- ── Comentarios de asiento ── --}}
            @if($comentariosAsiento->isNotEmpty())
            <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border);">
                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:var(--text-dim);margin-bottom:16px;">Valoraciones de asientos</p>
                @foreach($comentariosAsiento as $comentario)
                <div class="event__comment" style="margin-bottom:10px;">
                    <div class="event__comment-header">
                        <div class="event__comment-user">
                            <div class="event__comment-name">{{ $comentario['nombre'] ?? '' }} {{ $comentario['apellido'] ?? '' }}</div>
                            <div style="font-size:11px;color:var(--text-dim);margin-top:2px;">
                                Zona {{ $comentario['zona'] }} · Fila {{ $comentario['ejeY'] }}, Col {{ $comentario['ejeX'] }}
                            </div>
                        </div>
                        @if(!empty($comentario['valoracion']))
                        <div style="font-size:16px;flex-shrink:0;">
                            @for($i = 0; $i < (int)$comentario['valoracion']; $i++)⭐@endfor
                        </div>
                        @endif
                    </div>
                    @if(!empty($comentario['foto']))
                        <img src="{{ asset($comentario['foto']) }}" alt="Foto"
                             style="width:100%;max-height:300px;object-fit:cover;border-radius:10px;display:block;margin:10px 0;">
                    @endif
                    <p style="margin:4px 0 0;line-height:1.7;color:var(--text);">{{ $comentario['opinion'] }}</p>
                </div>
                @endforeach
            </div>
            @endif

        </div>

    </div>{{-- /.event --}}

    {{-- ═══════════════════════════════════════════════════
         COMMENT MODAL
         ═══════════════════════════════════════════════════ --}}
    <div x-show="modal.open" x-cloak
         style="position:fixed;inset:0;z-index:9000;display:flex;align-items:center;justify-content:center;">
        {{-- Backdrop --}}
        <div @click="modal.open=false"
             style="position:absolute;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(4px);"></div>

        {{-- Panel --}}
        <div style="position:relative;z-index:1;background:#0f0f1a;border:1px solid var(--border);border-radius:16px;padding:28px 32px;width:100%;max-width:440px;box-shadow:0 24px 60px rgba(0,0,0,.6);">

            {{-- Close button --}}
            <button @click="modal.open=false"
                    style="position:absolute;top:14px;right:18px;background:none;border:none;color:var(--text-dim);font-size:20px;cursor:pointer;line-height:1;">✕</button>

            {{-- Header --}}
            <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-bright);margin-bottom:4px;">
                Comentar asiento
            </h3>
            <p style="font-size:.8rem;color:var(--text-dim);margin-bottom:20px;">
                Zona <span x-text="modal.zona" style="color:var(--accent);font-weight:600;"></span> ·
                Fila <span x-text="modal.fila"></span>, Col <span x-text="modal.col"></span>
            </p>

            @if(session('success'))
                <div style="background:rgba(34,197,94,0.15);border:1px solid #22c55e;color:#86efac;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:.875rem;">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div style="background:rgba(239,68,68,0.15);border:1px solid #ef4444;color:#fca5a5;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:.875rem;">
                    @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                </div>
            @endif

            @auth
            <form method="POST" :action="'/asientos/' + modal.seatId + '/comentar'">
                @csrf
                <input type="hidden" name="foto" :value="modal.foto64">

                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:.8rem;color:var(--text-dim);margin-bottom:5px;">Tu opinión</label>
                    <textarea name="opinion" rows="3" required
                              style="width:100%;background:#1a1a2e;border:1px solid var(--border);color:var(--text-bright);border-radius:8px;padding:9px 12px;font-size:.875rem;resize:vertical;box-sizing:border-box;"
                              placeholder="Cuéntanos tu experiencia en este asiento..."></textarea>
                </div>

                <div style="display:flex;gap:16px;margin-bottom:14px;align-items:flex-end;">
                    <div style="flex:1;">
                        <label style="display:block;font-size:.8rem;color:var(--text-dim);margin-bottom:5px;">Valoración</label>
                        <div style="display:flex;gap:6px;" x-data="{ rating: 0 }">
                            <template x-for="star in [1,2,3,4,5]" :key="star">
                                <span @click="rating=star"
                                      :style="star <= rating ? 'cursor:pointer;font-size:24px;' : 'cursor:pointer;font-size:24px;opacity:.3;'"
                                      x-text="'⭐'"></span>
                            </template>
                            <input type="hidden" name="valoracion" :value="rating" x-ref="ratingInput">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom:18px;">
                    <label style="display:block;font-size:.8rem;color:var(--text-dim);margin-bottom:5px;">Foto (opcional)</label>
                    <input type="file" accept="image/*"
                           @change="const f=$event.target.files[0]; if(f){ const r=new FileReader(); r.onload=ev=>{ modal.foto64=ev.target.result; modal.preview=ev.target.result; }; r.readAsDataURL(f); }"
                           style="color:var(--text-dim);font-size:.8rem;width:100%;">
                    <template x-if="modal.preview">
                        <img :src="modal.preview" alt="preview"
                             style="margin-top:8px;max-height:100px;border-radius:8px;border:1px solid var(--border);display:block;">
                    </template>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit"
                            style="flex:1;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;padding:10px 0;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:.9rem;">
                        Enviar comentario
                    </button>
                    <button type="button" @click="modal.open=false"
                            style="padding:10px 18px;background:transparent;border:1px solid var(--border);color:var(--text-dim);border-radius:8px;cursor:pointer;font-size:.9rem;">
                        Cancelar
                    </button>
                </div>
            </form>
            @else
            <div style="text-align:center;padding:20px 0;">
                <p style="color:var(--text-dim);margin-bottom:14px;">Necesitas iniciar sesión para comentar.</p>
                <a href="{{ route('login') }}"
                   style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;padding:10px 24px;border-radius:8px;font-weight:600;text-decoration:none;font-size:.9rem;">
                    Iniciar sesión
                </a>
            </div>
            @endauth
        </div>
    </div>

</div>{{-- /.layout --}}

@push('scripts')
<script>
function seatSelector(asientosData) {
    return {
        asientos: asientosData,
        selected: [],
        modal: {
            open: false,
            seatId: null,
            zona: '',
            fila: '',
            col: '',
            foto64: '',
            preview: null
        },
        tooltip: {
            visible: false,
            x: 0,
            y: 0,
            comentarios: [],
            current: 0,
            hovered: false,
            timer: null
        },

        get total() {
            return this.selected.reduce(function (sum, s) {
                return sum + parseFloat(s.precio || 0);
            }, 0);
        },

        isSelected(idAsi) {
            return this.selected.some(function (s) { return s.idAsi === idAsi; });
        },

        findSeat(idAsi) {
            return this.asientos.find(function (a) { return a.idAsi === idAsi; });
        },

        toggleSeatById(idAsi) {
            var asiento = this.findSeat(idAsi);
            if (!asiento || asiento.estado === 'ocupado') return;
            var idx = this.selected.findIndex(function (s) { return s.idAsi === idAsi; });
            if (idx >= 0) {
                this.selected.splice(idx, 1);
            } else {
                this.selected.push(asiento);
            }
        },

        toggleSeat(asiento) {
            this.toggleSeatById(asiento.idAsi);
        },

        showTooltipById(event, idAsi) {
            var asiento = this.findSeat(idAsi);
            if (!asiento || !asiento.comentarios || asiento.comentarios.length === 0) return;
            this.tooltip.visible = true;
            this.tooltip.x = event.clientX + 10;
            this.tooltip.y = event.clientY + 10;
            this.tooltip.comentarios = asiento.comentarios;
            this.tooltip.current = 0;
        },

        openCommentModal(asiento) {
            if (!asiento) return;
            if (!window.__isAuth) { window.location.href = window.__loginUrl; return; }
            if (!asiento.esPropio) return;
            if (!window.__puedeCommentarAsiento) return;
            this.tooltip.visible = false;
            this.modal.seatId  = asiento.idAsi;
            this.modal.zona    = asiento.zona;
            this.modal.fila    = asiento.ejeY;
            this.modal.col     = asiento.ejeX;
            this.modal.foto64  = '';
            this.modal.preview = null;
            this.modal.open    = true;
        },

        hideTooltip() {
            var self = this;
            self.tooltip.timer = setTimeout(function () {
                if (!self.tooltip.hovered) {
                    self.tooltip.visible = false;
                }
            }, 200);
        }
    };
}
</script>
@endpush

@endsection
