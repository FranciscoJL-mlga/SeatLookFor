<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asiento;
use App\Models\Bloqueo;
use App\Models\Evento;
use App\Models\Reserva;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservaWebController extends Controller
{
    public function resumen(Evento $evento)
    {
        $evento->load('establecimiento');
        $idEve      = $evento->idEve;
        $idAsientos = session('asientos_seleccionados', []);

        if (empty($idAsientos) || session('evento_reserva') != $idEve) {
            return redirect()->route('evento.show', $evento->codigo)
                ->with('error', 'Selecciona al menos un asiento antes de continuar.');
        }

        Bloqueo::limpiarExpirados();

        $usuario = Auth::user();

        // Verificar que ningún asiento esté bloqueado por otro usuario
        foreach ($idAsientos as $idAsi) {
            if (Bloqueo::estaBlockeado($idAsi, $idEve, $usuario->idUsu)) {
                session()->forget(['asientos_seleccionados', 'evento_reserva']);
                return redirect()->route('evento.show', $evento->codigo)
                    ->with('error', 'Uno o más asientos han sido reservados por otro usuario. Por favor selecciona otros.');
            }
        }

        // Eliminar bloqueos anteriores de este usuario para este evento y crear nuevos
        Bloqueo::where('idUsu', $usuario->idUsu)->where('idEve', $idEve)->delete();

        $expiresAt = now()->addMinutes(5);
        foreach ($idAsientos as $idAsi) {
            Bloqueo::create([
                'idAsi'      => $idAsi,
                'idUsu'      => $usuario->idUsu,
                'idEve'      => $idEve,
                'expires_at' => $expiresAt,
            ]);
        }

        $evento->load('asientos');

        $idAsientosInt = array_map('intval', $idAsientos);

        $asientos = $evento->asientos
            ->whereIn('idAsi', $idAsientosInt)
            ->map(fn ($a) => [
                'idAsi'  => $a->idAsi,
                'zona'   => $a->zona,
                'ejeX'   => (int) $a->ejeX,
                'ejeY'   => (int) $a->ejeY,
                'precio' => $a->pivot->precio ?? 0,
            ]);

        $todosAsientos = $evento->asientos->map(fn ($a) => [
            'idAsi'        => $a->idAsi,
            'zona'         => $a->zona,
            'ejeX'         => (int) $a->ejeX,
            'ejeY'         => (int) $a->ejeY,
            'precio'       => $a->pivot->precio ?? 0,
            'seleccionado' => in_array($a->idAsi, $idAsientosInt),
        ]);

        $total = $asientos->sum('precio');

        return view('web.reserva.resumen', compact('evento', 'asientos', 'todosAsientos', 'total', 'expiresAt'));
    }

    public function liberar(Request $request)
    {
        $usuario = Auth::user();
        Bloqueo::where('idUsu', $usuario->idUsu)->delete();
        return response()->json(['ok' => true]);
    }

    public function crear(Request $request)
    {
        $request->validate([
            'idEve'      => 'required|integer|exists:evento,idEve',
            'idAsientos' => 'required|array|min:1',
            'idAsientos.*' => 'integer|exists:asiento,idAsi',
            'totalPrecio'=> 'required|numeric|min:0',
        ]);

        $usuario  = Auth::user();
        $reservas = [];

        DB::beginTransaction();
        try {
            foreach ($request->idAsientos as $idAsiento) {
                $reservas[] = Reserva::create([
                    'totalPrecio'  => $request->totalPrecio,
                    'fechaReserva' => now()->toDateString(),
                    'idAsi'        => $idAsiento,
                    'idUsu'        => $usuario->idUsu,
                    'idEve'        => $request->idEve,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la reserva. Inténtalo de nuevo.');
        }

        Bloqueo::where('idUsu', $usuario->idUsu)->where('idEve', $request->idEve)->delete();
        session()->forget(['asientos_seleccionados', 'evento_reserva']);

        $evento       = Evento::with('establecimiento')->findOrFail($request->idEve);
        $asientos     = Asiento::whereIn('idAsi', $request->idAsientos)->get();

        $pdf = Pdf::loadView('pdf.entrada', [
            'reservas'        => $reservas,
            'usuario'         => $usuario,
            'evento'          => $evento,
            'establecimiento' => $evento->establecimiento,
            'asientos'        => $asientos,
        ]);

        return $pdf->download('entrada_' . now()->timestamp . '.pdf');
    }
}
