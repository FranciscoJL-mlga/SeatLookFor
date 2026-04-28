<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Zona;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Demo establecimiento
        $idEst = DB::table('establecimiento')->insertGetId([
            'nombre'   => 'Teatro Demo SeatLookFor',
            'ubicacion'=> 'Av. de la Constitución 1, Madrid',
            'imagen'   => 'images/establecimientos/default.jpg',
            'demo'     => true,
        ]);

        // Asientos: escenario (fila 0), zonas A (filas 1-2), B (filas 3-5), C (filas 6-8)
        $zones = [
            'escenario' => ['rows' => [0],      'cols' => range(1, 8), 'precio' => 0.00],
            'A'         => ['rows' => [1, 2],    'cols' => range(1, 8), 'precio' => 50.00],
            'B'         => ['rows' => [3, 4, 5], 'cols' => range(1, 8), 'precio' => 30.00],
            'C'         => ['rows' => [6, 7, 8], 'cols' => range(1, 8), 'precio' => 15.00],
        ];

        $asientos = [];
        foreach ($zones as $zona => $config) {
            foreach ($config['rows'] as $row) {
                foreach ($config['cols'] as $col) {
                    $asientos[] = [
                        'estado' => 'libre',
                        'zona'   => $zona,
                        'ejeX'   => $col,
                        'ejeY'   => $row,
                        'precio' => $config['precio'],
                        'idEst'  => $idEst,
                    ];
                }
            }
        }

        DB::table('asiento')->insert($asientos);

        // Crear zonas (excluyendo escenario)
        foreach (['A', 'B', 'C'] as $zonaNombre) {
            Zona::firstOrCreate([
                'nombre' => $zonaNombre,
                'idEst'  => $idEst,
            ]);
        }

        // Demo evento
        $idEve = DB::table('evento')->insertGetId([
            'titulo'      => 'Gran Concierto Demo',
            'descripcion' => 'Este es un evento de demostración de SeatLookFor. Puedes explorar el mapa de asientos, gestionar precios por zona, habilitar/deshabilitar asientos y simular el flujo completo de reservas.',
            'fecha'       => '2026-12-31 20:00:00',
            'duracion'    => '02:00',
            'tipo'        => 'Concierto',
            'categoria'   => 'Musical',
            'estado'      => 'activo',
            'valoracion'  => 0,
            'ubicacion'   => 'Por determinar',
            'portada'     => 'images/eventos/default.jpg',
            'idEst'       => $idEst,
            'codigo'      => Str::random(8),
            'demo'        => true,
        ]);

        // Vincular todos los asientos (excepto escenario) al evento
        $asientoIds = DB::table('asiento')
            ->where('idEst', $idEst)
            ->where('zona', '!=', 'escenario')
            ->get();

        $pivots = [];
        foreach ($asientoIds as $asiento) {
            $precio = match($asiento->zona) {
                'A' => 50.00,
                'B' => 30.00,
                'C' => 15.00,
                default => 0.00,
            };
            $pivots[] = [
                'idAsi'  => $asiento->idAsi,
                'idEve'  => $idEve,
                'precio' => $precio,
            ];
        }

        if (!empty($pivots)) {
            DB::table('asientos_evento')->insert($pivots);
        }
    }
}
