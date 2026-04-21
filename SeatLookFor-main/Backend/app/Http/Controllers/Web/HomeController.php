<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Evento;

class HomeController extends Controller
{
    public function index()
    {
        $recientes = Evento::where('estado', 'activo')
            ->orderBy('fecha', 'desc')
            ->take(6)
            ->get();

        return view('web.home', compact('recientes'));
    }

    public function about()
    {
        return view('web.about');
    }
}
