<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

  public function store(LoginRequest $request)
{
    $request->authenticate();
    $request->session()->regenerate();

    return response()->json([
        'message' => 'Login correcto',
        'user' => Auth::user()
    ]);
}



public function logueoBack(LoginRequest $request)
{
    $request->authenticate();
    $request->session()->regenerate();

    return redirect()->intended(RouteServiceProvider::HOME);
}

public function destroy(Request $request)
{
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Logout correcto']);
}


public function logout(Request $request)
{
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login'); // O puedes redirigir a la página principal con "/"
}

}
