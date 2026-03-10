<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Evento;
use App\Models\Asiento;
use App\Models\Reserva;
use Illuminate\Http\Request;
use App\Models\Establecimiento;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreEventoRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateEventoRequest;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de SeatLookFor",
 *     description="API para la gestión de eventos y reservas de asientos",
 *     @OA\Contact(
 *         email="contacto@seatlookfor.com"
 *     )
 * )
 * 
 * @OA\Tag(
 *     name="Eventos",
 *     description="Endpoints relacionados con eventos"
 * )
 */
class EventoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/eventos",
     *     summary="Obtener lista de eventos",
     *     tags={"Eventos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de eventos obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="idEve", type="integer"),
     *                 @OA\Property(property="titulo", type="string"),
     *                 @OA\Property(property="fecha", type="string", format="date-time"),
     *                 @OA\Property(property="valoracion", type="number"),
     *                 @OA\Property(property="descripcion", type="string"),
     *                 @OA\Property(property="tipo", type="string", enum={"Teatro", "Orquesta", "Musical", "Concierto"}),
     *                 @OA\Property(property="categoria", type="string", enum={"Drama", "Familiar", "Clásica", "Musical", "Barroco", "Fantasía", "Suspenso", "Comedia"}),
     *                 @OA\Property(property="duracion", type="string", format="time"),
     *                 @OA\Property(property="ubicacion", type="string"),
     *                 @OA\Property(property="portada", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Evento::all(), 200);
    }

    /**
     * @OA\Get(
     *     path="/api/recientes/{id}",
     *     summary="Obtener eventos recientes",
     *     tags={"Eventos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Número de eventos recientes a obtener",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Eventos recientes obtenidos exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="recientes",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="idEve", type="integer"),
     *                     @OA\Property(property="titulo", type="string"),
     *                     @OA\Property(property="fecha", type="string", format="date-time"),
     *                     @OA\Property(property="valoracion", type="number"),
     *                     @OA\Property(property="descripcion", type="string"),
     *                     @OA\Property(property="tipo", type="string", enum={"Teatro", "Orquesta", "Musical", "Concierto"}),
     *                     @OA\Property(property="categoria", type="string", enum={"Drama", "Familiar", "Clásica", "Musical", "Barroco", "Fantasía", "Suspenso", "Comedia"}),
     *                     @OA\Property(property="duracion", type="string", format="time"),
     *                     @OA\Property(property="ubicacion", type="string"),
     *                     @OA\Property(property="portada", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron eventos"
     *     )
     * )
     */
    public function recientes($id)
    {
        $recientes = Evento::orderBy('fecha', 'desc')
            ->take($id)
            ->get();

        if ($recientes->isEmpty()) {
            return response()->json(['message' => 'No se encontraron eventos'], 404);
        }

        return response()->json(['recientes' => $recientes], 200);
    }

    public function listar()
    {
        $eventos = Evento::with('establecimiento')->paginate(6);
        return view('Evento.listadoEventos', compact('eventos'));
    }

    /**
     * @OA\Get(
     *     path="/api/eventos/{id}",
     *     summary="Obtener detalles de un evento",
     *     tags={"Eventos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del evento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del evento obtenidos exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="evento",
     *                 type="object",
     *                 @OA\Property(property="idEve", type="integer"),
     *                 @OA\Property(property="titulo", type="string"),
     *                 @OA\Property(property="fecha", type="string", format="date-time"),
     *                 @OA\Property(property="valoracion", type="number"),
     *                 @OA\Property(property="descripcion", type="string"),
     *                 @OA\Property(property="tipo", type="string", enum={"Teatro", "Orquesta", "Musical", "Concierto"}),
     *                 @OA\Property(property="categoria", type="string", enum={"Drama", "Familiar", "Clásica", "Musical", "Barroco", "Fantasía", "Suspenso", "Comedia"}),
     *                 @OA\Property(property="duracion", type="string", format="time"),
     *                 @OA\Property(property="ubicacion", type="string"),
     *                 @OA\Property(property="portada", type="string")
     *             ),
     *             @OA\Property(
     *                 property="establecimiento",
     *                 type="object",
     *                 @OA\Property(property="idEst", type="integer"),
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(property="ubicacion", type="string"),
     *                 @OA\Property(property="imagen", type="string"),
     *                 @OA\Property(property="tipo", type="string")
     *             ),
     *             @OA\Property(
     *                 property="asientos",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="idAsi", type="integer"),
     *                     @OA\Property(property="zona", type="string"),
     *                     @OA\Property(property="estado", type="string", enum={"libre", "ocupado"}),
     *                     @OA\Property(property="ejeX", type="integer"),
     *                     @OA\Property(property="ejeY", type="integer"),
     *                     @OA\Property(property="precio", type="number"),
     *                     @OA\Property(
     *                         property="comentarios",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="idUsu", type="integer"),
     *                             @OA\Property(property="nombre", type="string"),
     *                             @OA\Property(property="apellido", type="string"),
     *                             @OA\Property(property="opinion", type="string"),
     *                             @OA\Property(property="valoracion", type="number"),
     *                             @OA\Property(property="foto", type="string")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Evento no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function mostrar($id)
    {
        try {
            // Cargar evento con asientos y comentarios (desde tabla asientos_eventos)
            $evento = Evento::with(['asientos.usuariosComentaron', 'establecimiento'])->findOrFail($id);

            // Obtener los ID de los asientos reservados en este evento
            $asientosReservadosIds = Reserva::where('idEve', $id)->pluck('idAsi')->toArray();

            return response()->json([
                'evento' => [
                    'idEve' => $evento->idEve,
                    'titulo' => $evento->titulo,
                    'fecha' => $evento->fecha,
                    'valoracion' => $evento->valoracion,
                    'descripcion' => $evento->descripcion,
                    'tipo' => $evento->tipo,
                    'categoria' => $evento->categoria,
                    'duracion' => $evento->duracion,
                    'ubicacion' => $evento->ubicacion,
                    'portada' => $evento->portada,
                ],
                'establecimiento' => [
                    'idEst' => $evento->establecimiento->idEst,
                    'nombre' => $evento->establecimiento->nombre,
                    'ubicacion' => $evento->establecimiento->ubicacion,
                    'imagen' => $evento->establecimiento->imagen,
                    'tipo' => $evento->establecimiento->tipo,
                ],
                'asientos' => $evento->asientos->map(function ($a) use ($asientosReservadosIds) {
                    return [
                        'idAsi' => $a->idAsi,
                        'zona' => $a->zona,
                        'estado' => in_array($a->idAsi, $asientosReservadosIds) ? 'ocupado' : 'libre',
                        'ejeX' => $a->ejeX,
                        'ejeY' => $a->ejeY,
                        'precio' => $a->pivot->precio ?? null,
                        'comentarios' => $a->usuariosComentaron->map(function ($u) {
                            return [
                                'idUsu' => $u->idUsu,
                                'nombre' => $u->nombre,
                                'apellido'=>$u->apellido,
                                'opinion' => $u->pivot->opinion,
                                'valoracion' => $u->pivot->valoracion,
                                'foto' => $u->pivot->foto,
                            ];
                        }),
                    ];
                }),
            ]);

        } catch (\Exception $e) {
            // Si ocurre cualquier error, lo atrapamos
            return response()->json([
                'error' => 'Error al cargar los datos del evento.',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function formularioCrear()
    {
        $establecimientos = Establecimiento::with("asientos")->get();;
        return view('Evento.CrearEvento', compact('establecimientos'));
    }

    /**
     * @OA\Get(
     *     path="/api/zonas-por-establecimiento/{idEst}",
     *     summary="Obtener zonas de un establecimiento",
     *     tags={"Eventos"},
     *     @OA\Parameter(
     *         name="idEst",
     *         in="path",
     *         required=true,
     *         description="ID del establecimiento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zonas obtenidas exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="idZona", type="integer"),
     *                 @OA\Property(property="nombre", type="string"),
     *                 @OA\Property(
     *                     property="asientos",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="zona", type="string"),
     *                         @OA\Property(property="ejeX", type="integer"),
     *                         @OA\Property(property="ejeY", type="integer"),
     *                         @OA\Property(property="estado", type="string"),
     *                         @OA\Property(property="precio", type="number")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function obtenerZonas($idEst)
    {
        $asientos = Asiento::where('idEst', $idEst)->get();

        $zonasAgrupadas = $asientos->groupBy('zona'); // agrupamos por zona real
        $zonasFinales = [];

        foreach ($zonasAgrupadas as $nombreZona => $grupo) {
            // Busca o crea la zona por nombre y establecimiento
            $zona = \App\Models\Zona::firstOrCreate([
                'nombre' => $nombreZona,
                'idEst' => $idEst,
            ]);

            $zonasFinales[] = [
                'idZona' => $zona->idZona,
                'nombre' => $zona->nombre,
                'asientos' => $grupo->map(function ($a) {
                    return [
                        'id' => $a->idAsi,
                        'zona' => $a->zona,
                        'ejeX' => $a->ejeX,
                        'ejeY' => $a->ejeY,
                        'estado' => $a->estado,
                        'precio' => $a->precio,
                    ];
                })->values(),
            ];
        }

        return response()->json($zonasFinales);
    }

    public function guardar(Request $request)
    {
        try {
           $validator = Validator::make($request->all(), [
                    'titulo' => 'required|string|max:255',
                    'descripcion' => 'required|string',
                    'fecha' => 'required|date',
                    'duracion' => 'required|date_format:H:i',
                    'establecimiento_id' => 'required|exists:establecimiento,idEst',
                    'tipo' => 'required|in:Teatro,Orquesta,Musical,Concierto',
                    'categoria' => 'required|in:Drama,Familiar,Clásica,Musical,Barroco,Fantasía,Suspenso,Comedia',
                    'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);


            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (!$request->has('asientos_seleccionados') || count($request->input('asientos_seleccionados')) === 0) {
                return redirect()->back()->withErrors(['asientos_seleccionados' => 'Debe seleccionar al menos un asiento.'])->withInput();
            }

            $evento = new Evento();
            $evento->titulo = $request->titulo;
            $evento->descripcion = $request->descripcion;
            $evento->fecha = $request->fecha . ' ' . $request->hora;
            $evento->idEst = $request->establecimiento_id;
            $evento->estado = "activo";
            $evento->valoracion = 0;
            $evento->tipo = $request->tipo;
            $evento->categoria = $request->categoria;
            $evento->ubicacion = 'Por determinar';
            $evento->duracion = $request->duracion;

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $imagen->move(public_path('images/eventos'), $nombreImagen);
                $evento->portada = 'images/eventos/' . $nombreImagen;
            } else {
                $evento->portada = 'images/eventos/default.jpg';
            }

            $evento->save();

            $asientosSeleccionados = $request->input('asientos_seleccionados', []);
            $asientosZonas = $request->input('asientos_zonas', []); // key: idAsi => idZona
            $zonas = $request->input('zonas', []);
            $precios = $request->input('precios', []);

            $zonaPrecioMap = [];
            foreach ($zonas as $i => $zonaId) {
                $zonaPrecioMap[$zonaId] = $precios[$i] ?? 0;
            }

            foreach ($asientosSeleccionados as $idAsi) {
                $idZona = $asientosZonas[$idAsi] ?? null;
                if (!$idZona || !isset($zonaPrecioMap[$idZona])) continue;

                $evento->asientos()->syncWithoutDetaching([
                    $idAsi => ['precio' => $zonaPrecioMap[$idZona]]
                ]);
            }

            return redirect()->route('eventos.listado')->with('success', 'Evento creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar evento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear el evento.')->withInput();
        }
    }

    /**
     * @OA\Get(
     *     path="/api/eventos/{id}/comentarios",
     *     summary="Obtener comentarios de un evento",
     *     tags={"Eventos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del evento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comentarios obtenidos exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="idEve", type="integer"),
     *             @OA\Property(
     *                 property="comentarios",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="idCom", type="integer"),
     *                     @OA\Property(property="opinion", type="string"),
     *                     @OA\Property(property="valoracion", type="number"),
     *                     @OA\Property(property="foto", type="string"),
     *                     @OA\Property(property="idUsu", type="integer"),
     *                     @OA\Property(property="nombre", type="string"),
     *                     @OA\Property(property="apellido", type="string"),
     *                     @OA\Property(property="idAsi", type="integer"),
     *                     @OA\Property(property="zona", type="string"),
     *                     @OA\Property(property="ejeX", type="integer"),
     *                     @OA\Property(property="ejeY", type="integer")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function comentariosPorEvento($id)
    {
        try {
            // Obtener el evento con sus asientos y los usuarios que comentaron cada asiento
            $evento = Evento::with(['asientos.usuariosComentaron'])->findOrFail($id);

            // Recolectar todos los comentarios de los asientos del evento
            $comentarios = $evento->asientos->flatMap(function ($asiento) {
                return $asiento->usuariosComentaron->map(function ($usuario) use ($asiento) {
                    return [
                        'idCom' => $usuario->pivot->idCom ?? null,
                        'opinion' => $usuario->pivot->opinion,
                        'valoracion' => $usuario->pivot->valoracion,
                        'foto' => $usuario->pivot->foto,
                        'idUsu' => $usuario->idUsu,
                        'nombre' => $usuario->nombre,
                        'apellido' => $usuario->apellido,
                        'idAsi' => $asiento->idAsi,
                        'zona' => $asiento->zona,
                        'ejeX' => $asiento->ejeX,
                        'ejeY' => $asiento->ejeY,
                    ];
                });
            });

            return response()->json([
                'idEve' => $evento->idEve, 
                'comentarios' => $comentarios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudieron cargar los comentarios del evento.',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

public function ver($id)
{
    try {
        $evento = Evento::with(['establecimiento', 'ReservaDeEventos', 'asientos'])->findOrFail($id);
        return view('Evento.mostrarEvento', compact('evento'));
    } catch (\Exception $e) {
        Log::error('Error al cargar vista del evento: ' . $e->getMessage());
        return redirect()->route('eventos.listado')->withErrors(['error' => 'No se pudo mostrar el evento.']);

    }

public function eliminar($id)
{
    try {
        $evento = Evento::with('ReservaDeEventos')->findOrFail($id);

        // Eliminar primero las reservas asociadas
        foreach ($evento->ReservaDeEventos as $reserva) {
            $reserva->delete();
        }


            // Luego desvincular los asientos del evento
            $evento->asientos()->detach();

            // Finalmente eliminar el evento
            $evento->delete();

            return redirect()->route('eventos.listado')->with('success', 'Evento y reservas eliminados correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar el evento: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'No se pudo eliminar el evento.']);
        }
    }

    public function cambiarEstado(Request $request, $id)
    {
        try {
            $evento = Evento::findOrFail($id);

            $nuevoEstado = $request->input('estado');
            if (!in_array($nuevoEstado, ['activo', 'finalizado'])) {
                return redirect()->back()->withErrors(['error' => 'Estado inválido.']);
            }

            $evento->estado = $nuevoEstado;
            $evento->save();

            return redirect()->back()->with('success', 'Estado del evento actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del evento: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'No se pudo cambiar el estado del evento.']);
        }
    }
}

/**
 * @OA\Schema(
 *     schema="Evento",
 *     type="object",
 *     @OA\Property(property="idEve", type="integer"),
 *     @OA\Property(property="titulo", type="string"),
 *     @OA\Property(property="fecha", type="string", format="date-time"),
 *     @OA\Property(property="valoracion", type="number"),
 *     @OA\Property(property="descripcion", type="string"),
 *     @OA\Property(property="tipo", type="string", enum={"Teatro", "Orquesta", "Musical", "Concierto"}),
 *     @OA\Property(property="categoria", type="string", enum={"Drama", "Familiar", "Clásica", "Musical", "Barroco", "Fantasía", "Suspenso", "Comedia"}),
 *     @OA\Property(property="duracion", type="string", format="time"),
 *     @OA\Property(property="ubicacion", type="string"),
 *     @OA\Property(property="portada", type="string")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Establecimiento",
 *     type="object",
 *     @OA\Property(property="idEst", type="integer"),
 *     @OA\Property(property="nombre", type="string"),
 *     @OA\Property(property="ubicacion", type="string"),
 *     @OA\Property(property="imagen", type="string"),
 *     @OA\Property(property="tipo", type="string")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Asiento",
 *     type="object",
 *     @OA\Property(property="idAsi", type="integer"),
 *     @OA\Property(property="zona", type="string"),
 *     @OA\Property(property="estado", type="string", enum={"libre", "ocupado"}),
 *     @OA\Property(property="ejeX", type="integer"),
 *     @OA\Property(property="ejeY", type="integer"),
 *     @OA\Property(property="precio", type="number"),
 *     @OA\Property(
 *         property="comentarios",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Comentario")
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="Comentario",
 *     type="object",
 *     @OA\Property(property="idCom", type="integer"),
 *     @OA\Property(property="opinion", type="string"),
 *     @OA\Property(property="valoracion", type="number"),
 *     @OA\Property(property="foto", type="string"),
 *     @OA\Property(property="idUsu", type="integer"),
 *     @OA\Property(property="nombre", type="string"),
 *     @OA\Property(property="apellido", type="string"),
 *     @OA\Property(property="idAsi", type="integer"),
 *     @OA\Property(property="zona", type="string"),
 *     @OA\Property(property="ejeX", type="integer"),
 *     @OA\Property(property="ejeY", type="integer")
 * )
 */

    

