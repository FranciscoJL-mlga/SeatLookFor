<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Establecimiento;
use Illuminate\Support\Facades\DB;

class EstablecimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('establecimiento')->insert([
            [
                'ubicacion' => 'Calle Gran Bretaña',
                'nombre' => 'Teatro las Vegas',
                'imagen' => 'https://www.estudiosegui.com/wp-content/uploads/2017/02/teatro-cervantes-de-malaga.jpg'
                
            ],
            [
                'ubicacion' => 'Calle Cea Bermudez',
                'nombre' => 'Anfiteatro Alan Turing',
                'imagen' => 'https://www.ladrilleramecanizada.com/blog/wp-content/uploads/2019/04/Teatro-Opera-Garnier.jpg'
            ],
            [
                'ubicacion' => 'Calle Tarajal',
                'nombre' => 'Teatro Salino',
                'imagen' => 'https://media.timeout.com/images/100644343/750/422/image.jpg'
            ],
        ]);
    }
}
