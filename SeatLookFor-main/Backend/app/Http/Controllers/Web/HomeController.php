<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $query = Evento::where('estado', 'activo');

        if (!Auth::check() || !Auth::user()->es_demo) {
            $query->where('demo', false);
        }

        $recientes = $query->orderBy('fecha', 'desc')->take(6)->get();

        return view('web.home', compact('recientes'));
    }

    public function about()
    {
        return view('web.about');
    }
}
