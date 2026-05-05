<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\Evento;
use App\Models\Reserva;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $esDemo = auth()->user()->es_demo;

        $data = [
            'totalEstablecimientos' => Establecimiento::where('demo', $esDemo)->count(),
            'totalEventos'          => Evento::where('demo', $esDemo)->count(),
            'eventosActivos'        => Evento::where('demo', $esDemo)->where('estado', 'activo')->count(),
            'totalReservas'         => Reserva::whereIn('idEve',
                                           Evento::where('demo', $esDemo)->pluck('idEve')
                                       )->count(),
            'ultimosEventos'        => Evento::where('demo', $esDemo)
                                           ->with('establecimiento')
                                           ->orderBy('fecha', 'desc')
                                           ->take(5)
                                           ->get(),
        ];

        return view('dashboard', $data);
    }
} 