<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComentarioController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/asientos/{idAsi}/comentarios",
     *     operationId="comentarAsiento",
     *     summary="Crear un comentario para un asiento",
     *     description="Crea un nuevo comentario y valoración (0‑5) para el asiento indicado. El usuario debe estar autenticado.",
     *     tags={"Comentarios"},
     *
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="idAsi",
     *         description="ID del asiento",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=17)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"opinion","valoracion"},
     *             @OA\Property(property="opinion",   type="string", example="Muy cómodo 😍"),
     *             @OA\Property(property="valoracion",type="number", format="float", minimum=0, maximum=5, example=4.5),
     *             @OA\Property(property="foto",      type="string", nullable=true,
     *                          description="Imagen en base64 con cabecera data URI",
     *                          example="data:image/jpeg;base64,/9j/4AAQSkZJRgABA...")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Comentario guardado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="mensaje", type="string", example="Comentario guardado correctamente"),
     *             @OA\Property(
     *                 property="comentario",
     *                 type="object",
     *                 @OA\Property(property="idCom", type="integer"),
     *                 @OA\Property(property="opinion", type="string"),
     *                 @OA\Property(property="valoracion", type="number"),
     *                 @OA\Property(property="foto", type="string", nullable=true),
     *                 @OA\Property(property="idUsu", type="integer"),
     *                 @OA\Property(property="idAsi", type="integer"),
     *                 @OA\Property(property="idEve", type="integer", nullable=true),
     *                 @OA\Property(
     *                     property="usuario",
     *                     type="object",
     *                     @OA\Property(property="idUsu", type="integer"),
     *                     @OA\Property(property="nombre", type="string"),
     *                     @OA\Property(property="apellido", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Usuario no autenticado"),
     *     @OA\Response(response=404, description="El asiento no existe"),
     *     @OA\Response(response=422, description="Error de validación"),
     *     @OA\Response(response=500, description="Error interno")
     * )
     */


    public function comentar(Request $request, $idAsi)
    {
        try {
            Log::info('Intentando crear comentario', [
                'idAsi' => $idAsi,
                'request_data' => $request->all()
            ]);

            if (!$request->user()) {
                Log::error('Usuario no autenticado');
                return response()->json([
                    'mensaje' => 'Usuario no autenticado'
                ], 401);
            }

            $request->validate([
                'opinion' => 'required|string',
                'valoracion' => 'required|numeric|min:0|max:5',
                'foto' => 'nullable|string',
            ]);

            $usuario = $request->user();
            Log::info('Usuario autenticado', ['idUsu' => $usuario->idUsu]);

            // Verificar que el asiento existe
            $asiento = Asiento::findOrFail($idAsi);
            Log::info('Asiento encontrado', ['idAsi' => $asiento->idAsi]);

            // Procesar la imagen si existe
            $fotoPath = null;
            if ($request->has('foto') && $request->foto) {
                // Decodificar la imagen base64
                $image_parts = explode(";base64,", $request->foto);
                $image_base64 = base64_decode($image_parts[1]);
                
                // Generar nombre único para la imagen
                $imageName = Str::random(40) . '.jpg';
                
                // Guardar la imagen en storage
                Storage::disk('public')->put('images/comentarios/' . $imageName, $image_base64);
                
                // Guardar la ruta en la base de datos
                $fotoPath = 'storage/images/comentarios/' . $imageName;
            }

          $comentario = Comentario::create([
                'idUsu' => $usuario->idUsu,
                'idAsi' => $idAsi,
                'opinion' => $request->opinion,
                'valoracion' => $request->valoracion,
                'foto' => $fotoPath,
                'idEve' => null
            ]);

            Log::info('Comentario guardado exitosamente', ['idCom' => $comentario->idCom ?? 'nuevo']);

            return response()->json([
                'mensaje' => 'Comentario guardado correctamente',
                'comentario' => $comentario
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Asiento no encontrado', ['idAsi' => $idAsi]);
            return response()->json([
                'mensaje' => 'El asiento no existe'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación', ['errors' => $e->errors()]);
            return response()->json([
                'mensaje' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al guardar comentario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'mensaje' => 'Error al guardar el comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/asientos/{idAsi}/comentarios",
     *     operationId="listarComentariosAsiento",
     *     summary="Listar comentarios de un asiento",
     *     tags={"Comentarios"},
     *
     *     @OA\Parameter(
     *         name="idAsi",
     *         description="ID del asiento",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=17)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de comentarios",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="idCom", type="integer"),
     *                 @OA\Property(property="opinion", type="string"),
     *                 @OA\Property(property="valoracion", type="number"),
     *                 @OA\Property(property="foto", type="string", nullable=true),
     *                 @OA\Property(property="idUsu", type="integer"),
     *                 @OA\Property(property="idAsi", type="integer"),
     *                 @OA\Property(property="idEve", type="integer", nullable=true),
     *                 @OA\Property(
     *                     property="usuario",
     *                     type="object",
     *                     @OA\Property(property="idUsu", type="integer"),
     *                     @OA\Property(property="nombre", type="string"),
     *                     @OA\Property(property="apellido", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="El asiento no existe"),
     *     @OA\Response(response=500, description="Error interno")
     * )
     */


    public function getComentarios($idAsi)
    {
        try {
            $asiento = Asiento::findOrFail($idAsi);
            $comentarios = Comentario::with('usuario')
                ->where('idAsi', $idAsi)
                ->get();

            return response()->json($comentarios);
        } catch (\Exception $e) {
            Log::error('Error al obtener comentarios', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'mensaje' => 'Error al obtener los comentarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

/**
 * @OA\Schema(
 *     schema="Comentario",
 *     type="object",
 *     @OA\Property(property="idCom", type="integer"),
 *     @OA\Property(property="opinion", type="string"),
 *     @OA\Property(property="valoracion", type="number"),
 *     @OA\Property(property="foto", type="string", nullable=true),
 *     @OA\Property(property="idUsu", type="integer"),
 *     @OA\Property(property="idAsi", type="integer"),
 *     @OA\Property(property="idEve", type="integer", nullable=true),
 *     @OA\Property(
 *         property="usuario",
 *         type="object",
 *         @OA\Property(property="idUsu", type="integer"),
 *         @OA\Property(property="nombre", type="string"),
 *         @OA\Property(property="apellido", type="string")
 *     )
 * )
 */
