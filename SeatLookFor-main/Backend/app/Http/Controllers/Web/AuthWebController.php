<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthWebController extends Controller
{
    public function showLogin()
    {
        return view('web.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors(['email' => 'Credenciales incorrectas.'])->withInput();
        }

        Auth::login($usuario, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($usuario->admin && $usuario->es_demo) {
            $usuario->update(['demo_expires_at' => now()->addMinutes(15)]);
            return redirect()->route('dashboard');
        }

        return redirect()->intended(route('home'));
    }

    public function showRegistro()
    {
        return view('web.auth.registro');
    }

    public function registro(Request $request)
    {
        $request->validate([
            'nombre'               => 'required|string|max:100',
            'apellido'             => 'required|string|max:100',
            'email'                => 'required|email|unique:usuario,email',
            'password'             => 'required|string|min:8|confirmed',
            'password_confirmation'=> 'required',
        ]);

        $usuario = Usuario::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'estado'   => true,
            'admin'    => false,
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', '¡Bienvenido, ' . $usuario->nombre . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
