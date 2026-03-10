<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsientosSeeder extends Seeder
{
    public function run(): void
    {
        // Crear el establecimiento y obtener su ID
        $idEst = DB::table('establecimiento')->insertGetId([
                'ubicacion' => 'Calle Gran Bretaña',
                'nombre' => 'Teatro las Vegas',
                'imagen' => 'https://www.estudiosegui.com/wp-content/uploads/2017/02/teatro-cervantes-de-malaga.jpg'
        ]);

        $asientos = [];

        for ($i = 0; $i < 20; $i++) {
            $asientos[] = [
                'estado' => 'libre',
                'zona' => 'a',
                'ejeX' => $i % 5,
                'ejeY' => intdiv($i, 5),
                'precio' => 0.00,
                'idEst' => $idEst,
            ];
        }

        DB::table('asiento')->insert($asientos);
    }
}
