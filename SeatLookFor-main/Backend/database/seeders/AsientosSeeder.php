<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsientosSeeder extends Seeder
{
    public function run(): void
    {
        $establecimientos = DB::table('establecimiento')->pluck('idEst');

        foreach ($establecimientos as $idEst) {
            $asientos = [];

            // Zona A – Preferente (filas 1-3, columnas 3-12) – 30 asientos
            for ($fila = 1; $fila <= 3; $fila++) {
                for ($col = 3; $col <= 12; $col++) {
                    $asientos[] = [
                        'estado' => 'libre',
                        'zona'   => 'A',
                        'ejeX'   => $col,
                        'ejeY'   => $fila,
                        'precio' => 45.00,
                        'idEst'  => $idEst,
                    ];
                }
            }

            // Zona B – Platea (filas 4-6, columnas 3-14) – 36 asientos
            for ($fila = 4; $fila <= 6; $fila++) {
                for ($col = 3; $col <= 14; $col++) {
                    $asientos[] = [
                        'estado' => 'libre',
                        'zona'   => 'B',
                        'ejeX'   => $col,
                        'ejeY'   => $fila,
                        'precio' => 30.00,
                        'idEst'  => $idEst,
                    ];
                }
            }

            // Zona C – Anfiteatro (filas 7-9, columnas 3-14) – 36 asientos
            for ($fila = 7; $fila <= 9; $fila++) {
                for ($col = 3; $col <= 14; $col++) {
                    $asientos[] = [
                        'estado' => 'libre',
                        'zona'   => 'C',
                        'ejeX'   => $col,
                        'ejeY'   => $fila,
                        'precio' => 20.00,
                        'idEst'  => $idEst,
                    ];
                }
            }

            DB::table('asiento')->insert($asientos);
        }
    }
}
