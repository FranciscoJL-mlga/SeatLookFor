<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Reservas",
 *     description="API Endpoints para la gestión de reservas"
 * )
 */
class ReservaController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/reservas",
     *     summary="Crear una nueva reserva",
     *     tags={"Reservas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"totalPrecio", "fechaReserva", "idAsientos", "idEve"},
     *             @OA\Property(property="totalPrecio", type="number", format="float", example=50.00),
     *             @OA\Property(property="fechaReserva", type="string", format="date-time", example="2024-03-20T15:00:00Z"),
     *             @OA\Property(
     *                 property="idAsientos",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1)
     *             ),
     *             @OA\Property(property="idEve", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserva creada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Reserva creada exitosamente"),
     *             @OA\Property(
     *                 property="reservas",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="idRes", type="integer", example=1),
     *                     @OA\Property(property="totalPrecio", type="number", format="float", example=50.00),
     *                     @OA\Property(property="fechaReserva", type="string", format="date-time"),
     *                     @OA\Property(property="idAsi", type="integer", example=1),
     *                     @OA\Property(property="idUsu", type="integer", example=1),
     *                     @OA\Property(property="idEve", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="totalPrecio",
     *                     type="array",
     *                     @OA\Items(type="string", example="El campo totalPrecio es obligatorio")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Error al crear reservas")
     *         )
     *     )
     * )
     */
    public function crearReserva(Request $request)
    {
        $validated = $request->validate([
            'totalPrecio' => 'required|numeric|min:0',
            'fechaReserva' => 'required|date',
            'idAsientos' => 'required|array|min:1',
            'idAsientos.*' => 'integer|exists:asiento,idAsi',
            'idEve' => 'required|integer|exists:evento,idEve',
        ]);

        $usuario = $request->user();
        $reservas = [];

        DB::beginTransaction();
        try {
            foreach ($validated['idAsientos'] as $idAsiento) {
                $reserva = Reserva::create([
                    'totalPrecio' => $validated['totalPrecio'],
                    'fechaReserva' => $validated['fechaReserva'],
                    'idAsi' => $idAsiento,
                    'idUsu' => $usuario->idUsu,
                    'idEve' => $validated['idEve'],
                ]);

                $reservas[] = $reserva;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear reservas.'], 500);
        }

        // Cargar datos relacionados
        $evento = \App\Models\Evento::with('establecimiento')->findOrFail($validated['idEve']);
        $asientos = \App\Models\Asiento::whereIn('idAsi', $validated['idAsientos'])->get();

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.entrada', [
            'reservas' => $reservas,
            'usuario' => $usuario,
            'evento' => $evento,
            'establecimiento' => $evento->establecimiento,
            'asientos' => $asientos,
        ]);

        $filename = 'entrada_' . now()->timestamp . '.pdf';

        // DEVOLVER PDF PARA DESCARGA DIRECTA
        return $pdf->download($filename);
    }
}
