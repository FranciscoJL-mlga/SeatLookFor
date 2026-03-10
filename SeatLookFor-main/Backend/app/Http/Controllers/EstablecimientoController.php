<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\Zona; // Asegúrate de importar el modelo

use Illuminate\Http\Request;
use App\Models\Establecimiento;
use App\Http\Requests\StoreEstablecimientoRequest;
use App\Http\Requests\UpdateEstablecimientoRequest;

/**
 * @OA\Schema(
 *     schema="Establecimiento",
 *     type="object",
 *     required={"idEst", "nombre", "ubicacion", "imagen"},
 *     @OA\Property(property="idEst", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", maxLength=255, example="Palacio de los Deportes"),
 *     @OA\Property(property="ubicacion", type="string", maxLength=255, example="Madrid, España"),
 *     @OA\Property(property="imagen", type="string", maxLength=255, example="https://example.com/foto.jpg"),
 *     @OA\Property(
 *         property="asientos",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="idAsi", type="integer", example=1),
 *             @OA\Property(property="estado", type="string", example="libre"),
 *             @OA\Property(property="zona", type="string", maxLength=5, example="A"),
 *             @OA\Property(property="ejeX", type="integer", example=1),
 *             @OA\Property(property="ejeY", type="integer", example=1),
 *             @OA\Property(property="precio", type="number", format="float", example=50.00)
 *         )
 *     )
 * )
 */
class EstablecimientoController extends Controller
{
  
/**
 * @OA\Get(
 *     path="/establecimientos",
 *     operationId="listarEstablecimientos",
 *     summary="Listar establecimientos (6 por página)",
 *     description="Devuelve la vista Blade `Establecimiento.listadoEstablecimiento` con la paginación.",
 *     tags={"Establecimientos"},
 *
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Número de página (paginación de 6 elementos)",
 *         required=false,
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Vista HTML con el listado paginado",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Establecimiento")
 *             ),
 *             @OA\Property(property="current_page", type="integer", example=1),
 *             @OA\Property(property="per_page", type="integer", example=6),
 *             @OA\Property(property="total", type="integer", example=10)
 *         )
 *     )
 * )
 */
    /**
     * Listado de los establecimientos 
     */
    public function listar(Request $request)
    {   

   // Carga todos los establecimientos, 6 por página
    $establecimientos = Establecimiento::paginate(6);

    return view('Establecimiento.listadoEstablecimiento', compact('establecimientos'));
    }


/*         public function guardarAsientos(Request $request)
    {
        foreach ($request->asientos as $asiento) {
            Asiento::create([
                'establecimiento_id' => $request->establecimiento_id,
                'fila' => $asiento['fila'],
                'columna' => $asiento['columna'],
                'codigo' => $asiento['codigo'],
            ]);
        }

        return response()->json(['message' => 'Asientos guardados correctamente.']);
    } */

    /**
     * Guarda los datos del establecimiento nuevo y sus asientos
     */

/**
 * @OA\Post(
 *     path="/api/establecimientos",
 *     operationId="crearEstablecimiento",
 *     summary="Crear un establecimiento con sus zonas y asientos",
 *     description="Crea un nuevo establecimiento y registra zonas (se ignora 'escenario'). Devuelve JSON con el establecimiento creado.",
 *     tags={"Establecimientos"},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nombre","ubicacion","imagen","asientos"},
 *             @OA\Property(property="nombre",    type="string", maxLength=255, example="Palacio de los Deportes"),
 *             @OA\Property(property="ubicacion", type="string", maxLength=255, example="Madrid, España"),
 *             @OA\Property(property="imagen",    type="string", maxLength=255, example="https://example.com/foto.jpg"),
 *             @OA\Property(
 *                 property="asientos",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     required={"fila","columna","zona"},
 *                     @OA\Property(property="fila",    type="integer", example=1),
 *                     @OA\Property(property="columna", type="integer", example=3),
 *                     @OA\Property(property="zona",    type="string",  maxLength=5, example="A")
 *                 ),
 *                 example={
 *                     {"fila":1,"columna":1,"zona":"A"},
 *                     {"fila":1,"columna":2,"zona":"A"},
 *                     {"fila":1,"columna":3,"zona":"escenario"}
 *                 }
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Establecimiento creado correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="mensaje", type="string", example="Establecimiento guardado correctamente"),
 *             @OA\Property(property="establecimiento", ref="#/components/schemas/Establecimiento")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Error de validación"),
 *     @OA\Response(response=500, description="Error interno")
 * )
 */
public function guardar(Request $request)
{
    // Validar establecimiento
    $request->validate([
        'nombre' => 'required|string|max:255',
        'ubicacion' => 'required|string|max:255',
        'imagen' => 'required|string|max:255',
        
    ]);

    // Crear el establecimiento
    $establecimiento = Establecimiento::create([
        'nombre' => $request->input('nombre'),
        'ubicacion' => $request->input('ubicacion'),
        'imagen' => $request->input('imagen')
    ]);

    // Procesar los asientos desde el JSON
    $asientosRaw = $request->input('asientos');
    $asientos = json_decode($asientosRaw, true);

    if (!is_array($asientos) || empty($asientos)) {
        return back()->with('error', 'No se proporcionaron asientos válidos.')->withInput();
    }

    // Extraer zonas únicas, excluyendo el "escenario"
    $zonas = collect($asientos)
        ->pluck('zona')
        ->filter(fn($z) => strtolower($z) !== 'escenario')
        ->unique();
  
        
        
   foreach ($zonas as $zonaNombre) {
    if (strlen($zonaNombre) > 5) {
        return back()->withErrors(['zona' => "La zona '$zonaNombre' no puede tener más de 5 caracteres."])->withInput();
    }

    Zona::firstOrCreate([
        'nombre' => $zonaNombre,
        'idEst' => $establecimiento->idEst,
    ]);
}


    // Guardar los asientos
    foreach ($asientos as $item) {
        if (!isset($item['estado'], $item['zona'], $item['ejeX'], $item['ejeY'], $item['precio'])) {
            continue;
        }

        Asiento::create([
            'estado' => $item['estado'],
            'zona' => $item['zona'],
            'ejeX' => $item['ejeX'],
            'ejeY' => $item['ejeY'],
            'precio' => $item['precio'],
            'idEst' => $establecimiento->idEst,
        ]);
    }

    return to_route('establecimiento.listado');
}


/**
 * @OA\Get(
 *     path="/establecimientos/{idEst}",
 *     operationId="mostrarEstablecimiento",
 *     summary="Mostrar detalles de un establecimiento",
 *     description="Devuelve la vista Blade `Establecimiento.mostrarEstablecimiento` con los detalles del establecimiento, incluyendo sus asientos y eventos.",
 *     tags={"Establecimientos"},
 *
 *     @OA\Parameter(
 *         name="idEst",
 *         in="path",
 *         required=true,
 *         description="ID del establecimiento",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Vista HTML con los detalles del establecimiento",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="establecimiento", ref="#/components/schemas/Establecimiento"),
 *             @OA\Property(
 *                 property="asientos",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="idAsi", type="integer", example=1),
 *                     @OA\Property(property="estado", type="string", example="libre"),
 *                     @OA\Property(property="zona", type="string", example="A"),
 *                     @OA\Property(property="ejeX", type="integer", example=1),
 *                     @OA\Property(property="ejeY", type="integer", example=1),
 *                     @OA\Property(property="precio", type="number", format="float", example=50.00)
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="eventos",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="idEve", type="integer", example=1),
 *                     @OA\Property(property="nombre", type="string", example="Concierto de Rock")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Establecimiento no encontrado"
 *     )
 * )
 */
public function mostrar($idEst)
{
    $establecimiento = Establecimiento::with(['asientos', 'eventos'])->findOrFail($idEst);

    return view('Establecimiento.mostrarEstablecimiento', compact('establecimiento'));
}


/**
 * @OA\Delete(
 *     path="/establecimientos/{id}",
 *     operationId="eliminarEstablecimiento",
 *     summary="Eliminar un establecimiento",
 *     description="Elimina un establecimiento y sus asientos asociados. No se puede eliminar si tiene eventos asociados.",
 *     tags={"Establecimientos"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID del establecimiento a eliminar",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *
 *     @OA\Response(
 *         response=302,
 *         description="Establecimiento eliminado correctamente",
 *         @OA\Header(
 *             header="Location",
 *             description="URL de redirección al listado",
 *             @OA\Schema(type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="No se puede eliminar el establecimiento",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="No se puede eliminar el establecimiento porque tiene eventos asociados")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Establecimiento no encontrado"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="No se pudo eliminar el establecimiento")
 *         )
 *     )
 * )
 */
public function eliminar($id)
{
    try {
        $establecimiento = Establecimiento::with(['asientos.usuariosComentaron', 'eventos'])->findOrFail($id);

        // Si hay eventos asociados, no permitir borrar
        if ($establecimiento->eventos->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'No se puede eliminar el establecimiento porque tiene eventos asociados.']);
        }

        foreach ($establecimiento->asientos as $asiento) {
            $asiento->usuariosComentaron()->detach();
            $asiento->eventos()->detach();
            $asiento->delete();
        }

        $establecimiento->delete();

        return redirect()->route('establecimiento.listado')->with('success', 'Establecimiento y sus asientos fueron eliminados correctamente.');
    } catch (\Exception $e) {
        \Log::error('Error al eliminar el establecimiento: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'No se pudo eliminar el establecimiento.']);
    }
}


}
