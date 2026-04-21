<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstablecimientoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('establecimiento')->insert([
            [
                'nombre'    => 'Teatro Cervantes',
                'ubicacion' => 'C/ Ramos Marín s/n, Málaga',
                'imagen'    => 'https://picsum.photos/seed/teatro-cervantes/800/450',
            ],
            [
                'nombre'    => 'Auditorio Nacional de Música',
                'ubicacion' => 'C/ Príncipe de Vergara 146, Madrid',
                'imagen'    => 'https://picsum.photos/seed/auditorio-madrid/800/450',
            ],
            [
                'nombre'    => 'Gran Teatro del Liceu',
                'ubicacion' => 'La Rambla 51-59, Barcelona',
                'imagen'    => 'https://picsum.photos/seed/liceu-barcelona/800/450',
            ],
            [
                'nombre'    => 'Teatro de la Maestranza',
                'ubicacion' => 'Paseo de Colón 22, Sevilla',
                'imagen'    => 'https://picsum.photos/seed/maestranza-sevilla/800/450',
            ],
        ]);
    }
}
