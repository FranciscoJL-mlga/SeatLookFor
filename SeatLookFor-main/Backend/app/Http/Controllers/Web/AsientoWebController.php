<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asiento;
use App\Models\Comentario;
use App\Models\Evento;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AsientoWebController extends Controller
{
    public function index(Evento $evento)
    {
        $evento->load(['asientos.usuariosComentaron', 'establecimiento']);

        $asientosReservadosIds = Reserva::where('idEve', $evento->idEve)->pluck('idAsi')->toArray();

        $asientos = $evento->asientos->map(function ($a) use ($asientosReservadosIds) {
            return [
                'idAsi'     => $a->idAsi,
                'zona'      => $a->zona,
                'estado'    => in_array($a->idAsi, $asientosReservadosIds) ? 'ocupado' : 'libre',
                'ejeX'      => (int) $a->ejeX,
                'ejeY'      => (int) $a->ejeY,
                'precio'    => $a->pivot->precio ?? 0,
                'comentarios' => $a->usuariosComentaron->map(fn ($u) => [
                    'nombre'    => $u->nombre,
                    'apellido'  => $u->apellido,
                    'opinion'   => $u->pivot->opinion,
                    'valoracion'=> $u->pivot->valoracion,
                    'foto'      => $u->pivot->foto,
                ])->toArray(),
            ];
        })->toArray();

        return view('web.asientos.index', compact('evento', 'asientos'));
    }

    public function seleccionar(Request $request)
    {
        $request->validate([
            'idEvento'   => 'required|integer|exists:evento,idEve',
            'idAsientos' => 'required|array|min:1',
            'idAsientos.*' => 'integer|exists:asiento,idAsi',
        ]);

        $evento = Evento::findOrFail($request->idEvento);

        session(['asientos_seleccionados' => $request->idAsientos]);
        session(['evento_reserva' => $evento->idEve]);

        return redirect()->route('reserva.resumen', $evento->codigo);
    }

    public function comentar(Request $request, $id)
    {
        $request->validate([
            'opinion'    => 'required|string',
            'valoracion' => 'required|numeric|min:1|max:5',
            'foto'       => 'nullable|string',
        ]);

        $asiento = Asiento::findOrFail($id);
        $usuario = Auth::user();

        $reserva = Reserva::where('idAsi', $asiento->idAsi)
            ->where('idUsu', $usuario->idUsu)
            ->with('evento')
            ->first();

        if (!$reserva) {
            return back()->with('error', 'Solo puedes comentar en asientos que hayas reservado.');
        }

        // Los comentarios de asiento solo se permiten tras finalizar el evento
        if (!$reserva->evento || $reserva->evento->estado !== 'finalizado') {
            return back()->with('error', 'Solo puedes comentar en tu asiento una vez finalizado el evento.');
        }

        if (\Carbon\Carbon::parse($reserva->evento->fecha)->addMonth()->isPast()) {
            return back()->with('error', 'El plazo para comentar este evento ha expirado.');
        }

        $fotoPath = $this->guardarFoto($request->foto);

        Comentario::create([
            'idUsu'     => $usuario->idUsu,
            'idAsi'     => $asiento->idAsi,
            'idEve'     => $reserva->evento->idEve,
            'opinion'   => $request->opinion,
            'valoracion'=> $request->valoracion,
            'foto'      => $fotoPath,
        ]);

        return back()->with('success', 'Comentario añadido correctamente.');
    }

    public function comentarEvento(Request $request, $idEve)
    {
        $request->validate(['opinion' => 'required|string']);

        $evento = Evento::findOrFail($idEve);
        $usuario = Auth::user();

        Comentario::create([
            'idUsu'    => $usuario->idUsu,
            'idEve'    => $evento->idEve,
            'opinion'  => $request->opinion,
            'valoracion' => null,
            'foto'     => null,
            'idAsi'    => null,
        ]);

        return back()->with('success', 'Comentario publicado.');
    }

    public function responder(Request $request, $idParent)
    {
        $request->validate(['opinion' => 'required|string']);

        $parent = Comentario::findOrFail($idParent);
        $usuario = Auth::user();

        Comentario::create([
            'idUsu'    => $usuario->idUsu,
            'idEve'    => $parent->idEve,
            'idAsi'    => null,
            'opinion'  => $request->opinion,
            'valoracion' => null,
            'foto'     => null,
            'idParent' => $parent->idCom,
        ]);

        return back()->with('success', 'Respuesta publicada.');
    }

    private function guardarFoto(?string $base64): ?string
    {
        if (!$base64) return null;
        $parts = explode(';base64,', $base64);
        if (count($parts) < 2) return null;
        $decoded = base64_decode($parts[1]);
        $nombre = Str::random(40) . '.jpg';
        Storage::disk('public')->put('images/comentarios/' . $nombre, $decoded);
        return 'storage/images/comentarios/' . $nombre;
    }

    public function eliminarComentario($id)
    {
        $comentario = Comentario::findOrFail($id);

        if ($comentario->idUsu !== Auth::user()->idUsu) {
            abort(403);
        }

        $comentario->delete();

        return back()->with('success', 'Comentario eliminado.');
    }
}
