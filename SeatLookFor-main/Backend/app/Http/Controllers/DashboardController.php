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
        $data = [
            'totalEstablecimientos' => Establecimiento::count(),
            'totalEventos' => Evento::count(),
            'eventosActivos' => Evento::where('estado', 'activo')->count(),
            'totalReservas' => Reserva::count(),
            'ultimosEventos' => Evento::with('establecimiento')
                ->orderBy('fecha', 'desc')
                ->take(5)
                ->get()
        ];

        return view('dashboard', $data);
    }
} 