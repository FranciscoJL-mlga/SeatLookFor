<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = DB::table('usuario')->where('admin', false)->pluck('idUsu')->toArray();
        $eventos  = DB::table('evento')->get();

        $reservas  = [];
        $usados    = []; // evitar duplicado (idAsi, idEve)

        foreach ($eventos as $idx => $evento) {
            // 5 asientos ocupados por evento — zona B central
            $asientos = DB::table('asiento')
                ->where('idEst', $evento->idEst)
                ->where('zona', 'B')
                ->orderBy('idAsi')
                ->limit(5)
                ->get();

            $precio = DB::table('asientos_evento')
                ->where('idEve', $evento->idEve)
                ->value('precio') ?? 30.00;

            foreach ($asientos as $j => $asiento) {
                $key = $asiento->idAsi . '-' . $evento->idEve;
                if (isset($usados[$key])) continue;
                $usados[$key] = true;

                $idUsu = $usuarios[($idx + $j) % count($usuarios)];
                $reservas[] = [
                    'fechaReserva' => date('Y-m-d', strtotime("-{$j} days")),
                    'totalPrecio'  => $precio,
                    'idEve'        => $evento->idEve,
                    'idAsi'        => $asiento->idAsi,
                    'idUsu'        => $idUsu,
                ];
            }
        }

        if (!empty($reservas)) {
            DB::table('reserva')->insert($reservas);
        }
    }
}
