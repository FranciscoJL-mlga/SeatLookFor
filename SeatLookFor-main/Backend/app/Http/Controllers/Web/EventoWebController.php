<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Reserva;
use Illuminate\Http\Request;

class EventoWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Evento::where('estado', 'activo');

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', '>=', $request->fecha);
        }
        if ($request->filled('valoracion')) {
            $query->where('valoracion', '>=', $request->valoracion);
        }
        if ($request->filled('buscar')) {
            $query->where('titulo', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('orden') && $request->orden === 'valoracion') {
            $query->orderBy('valoracion', 'desc');
        } else {
            $query->orderBy('fecha', 'asc');
        }

        $eventos = $query->paginate(12)->withQueryString();

        $tipos = ['Teatro', 'Orquesta', 'Musical', 'Concierto'];
        $categorias = ['Drama', 'Familiar', 'Clásica', 'Musical', 'Barroco', 'Fantasía', 'Suspenso', 'Comedia'];

        return view('web.eventos.index', compact('eventos', 'tipos', 'categorias'));
    }

    public function show($id)
    {
        $evento = Evento::with(['asientos.usuariosComentaron', 'establecimiento'])->findOrFail($id);
        $idEve = $evento->idEve;

        // Eventos finalizados: solo accesibles para usuarios con reserva en ese evento
        if ($evento->estado === 'finalizado') {
            if (!auth()->check()) {
                return redirect()->route('login');
            }
            $tieneReserva = Reserva::where('idEve', $idEve)->where('idUsu', auth()->id())->exists();
            if (!$tieneReserva) {
                abort(403, 'Este evento ha finalizado y no tienes entrada para él.');
            }
        }

        \App\Models\Bloqueo::limpiarExpirados();

        $asientosReservadosIds = Reserva::where('idEve', $idEve)->pluck('idAsi')->toArray();

        // Asientos bloqueados por otros usuarios actualmente
        $asientosBloqueadosIds = \App\Models\Bloqueo::where('idEve', $idEve)
            ->where('expires_at', '>', now())
            ->when(auth()->check(), fn ($q) => $q->where('idUsu', '!=', auth()->id()))
            ->pluck('idAsi')
            ->toArray();

        // IDs de asientos que el usuario actual ha reservado en este evento
        $misAsientosIds = auth()->check()
            ? Reserva::where('idEve', $idEve)->where('idUsu', auth()->id())->pluck('idAsi')->toArray()
            : [];

        // Puede comentar si el evento es activo, o si es finalizado y no han pasado más de 30 días
        $puedeCommentar = $evento->estado === 'activo'
            || ($evento->estado === 'finalizado' && \Carbon\Carbon::parse($evento->fecha)->addMonth()->isFuture());

        // Cargamos comentarios de asiento directamente (sin pivot, filtrando respuestas)
        $comentariosPorAsiento = \App\Models\Comentario::with('usuario')
            ->whereIn('idAsi', $evento->asientos->pluck('idAsi'))
            ->whereNull('idParent')
            ->get()
            ->groupBy('idAsi');

        $asientos = $evento->asientos->map(function ($a) use ($asientosReservadosIds, $misAsientosIds, $comentariosPorAsiento, $asientosBloqueadosIds) {
            $coms = $comentariosPorAsiento->get($a->idAsi, collect());
            $estado = in_array($a->idAsi, $asientosReservadosIds) ? 'ocupado'
                    : (in_array($a->idAsi, $asientosBloqueadosIds) ? 'bloqueado' : 'libre');
            return [
                'idAsi'    => $a->idAsi,
                'zona'     => $a->zona,
                'estado'   => $estado,
                'ejeX'     => $a->ejeX,
                'ejeY'     => $a->ejeY,
                'precio'   => $a->pivot->precio ?? null,
                'esPropio' => in_array($a->idAsi, $misAsientosIds),
                'comentarios' => $coms->map(fn ($c) => [
                    'nombre'    => $c->usuario->nombre ?? '',
                    'apellido'  => $c->usuario->apellido ?? '',
                    'opinion'   => $c->opinion,
                    'valoracion'=> $c->valoracion,
                    'foto'      => $c->foto,
                ])->values(),
            ];
        });

        // Comentarios del evento (no ligados a asiento, sin ser respuestas)
        $comentariosEvento = \App\Models\Comentario::with(['usuario', 'respuestas'])
            ->where('idEve', $idEve)
            ->whereNull('idAsi')
            ->whereNull('idParent')
            ->orderBy('idCom', 'asc')
            ->get();

        return view('web.eventos.show', compact('evento', 'asientos', 'puedeCommentar', 'comentariosEvento'));
    }
}
