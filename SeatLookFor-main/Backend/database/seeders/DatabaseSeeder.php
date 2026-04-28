<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar comprobaciones de claves foráneas para truncar
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('reserva')->truncate();
        DB::table('comentario')->truncate();
        DB::table('asientos_evento')->truncate();
        DB::table('asiento')->truncate();
        DB::table('evento')->truncate();
        DB::table('establecimiento')->truncate();
        DB::table('usuario')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->call([
            EstablecimientoSeeder::class,
            UsuarioSeeder::class,
            EventoSeeder::class,
            AsientosSeeder::class,
        ]);

        // Vincular todos los asientos de cada establecimiento a sus eventos
        $this->linkAsientosEventos();

        $this->call([
            ComentariosSeeder::class,
            ReservaSeeder::class,
            DemoSeeder::class,
        ]);
    }

    private function linkAsientosEventos(): void
    {
        $eventos = DB::table('evento')->get();

        foreach ($eventos as $evento) {
            $asientos = DB::table('asiento')
                ->where('idEst', $evento->idEst)
                ->get();

            $pivots = [];
            foreach ($asientos as $asiento) {
                $pivots[] = [
                    'idAsi'  => $asiento->idAsi,
                    'idEve'  => $evento->idEve,
                    'precio' => $asiento->precio, // precio base por zona
                ];
            }

            if (!empty($pivots)) {
                DB::table('asientos_evento')->insert($pivots);
            }
        }
    }
}
