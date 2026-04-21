<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $reservas = Reserva::with(['evento', 'asiento'])
            ->where('idUsu', $usuario->idUsu)
            ->orderBy('fechaReserva', 'desc')
            ->get();

        // Eventos finalizados a los que el usuario asistió, dentro del último mes
        $eventosVisitados = Reserva::with('evento')
            ->where('idUsu', $usuario->idUsu)
            ->whereHas('evento', function ($q) {
                $q->where('estado', 'finalizado')
                  ->where('fecha', '>=', now()->subMonth());
            })
            ->get()
            ->pluck('evento')
            ->unique('idEve')
            ->values();

        return view('web.usuario.perfil', compact('usuario', 'reservas', 'eventosVisitados'));
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual'  => 'required|string',
            'password_nuevo'   => 'required|string|min:8|confirmed',
        ], [
            'password_nuevo.min'       => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password_nuevo.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $usuario = Auth::user();

        if (!Hash::check($request->password_actual, $usuario->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.'])->with('seccion', 'configuracion');
        }

        $usuario->password = Hash::make($request->password_nuevo);
        $usuario->save();

        return back()->with('password_success', 'Contraseña actualizada correctamente.')->with('seccion', 'configuracion');
    }

    public function eliminarCuenta(Request $request)
    {
        $request->validate([
            'password_confirm' => 'required|string',
        ]);

        $usuario = Auth::user();

        if (!Hash::check($request->password_confirm, $usuario->password)) {
            return back()->withErrors(['password_confirm' => 'Contraseña incorrecta.'])->with('seccion', 'configuracion');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $usuario->delete();

        return redirect()->route('home')->with('success', 'Tu cuenta ha sido eliminada.');
    }
}
