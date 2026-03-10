<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComentarioSeeder extends Seeder
{
    public function run()
    {
        $comentarios = [];

        for ($i = 1; $i <= 20; $i++) {
            $comentarios[] = [
                'opinion' => 'Este es un comentario de prueba número ' . $i,
                'valoracion' => rand(30, 50) / 10, // entre 3.0 y 5.0
                'foto' => 'comentario' . $i . '.jpg',
                'idUsu' => rand(1, 5), // suponiendo que hay al menos 5 usuarios
                'idAsi' => rand(1, 20), // suponiendo que hay 20 asientos
            ];
        }

        DB::table('comentario')->insert($comentarios);
    }
}
