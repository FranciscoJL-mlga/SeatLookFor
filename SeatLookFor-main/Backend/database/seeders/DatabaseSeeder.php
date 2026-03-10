<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Asiento;
use App\Models\Reserva;
use App\Models\Comentario;
use App\Models\Evento;
use App\Models\Establecimiento;
use Database\Seeders\AsientosSeeder;
use Database\Seeders\ComentariosSeeder;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   

        //Asiento::factory()->count(10)->create();
        //Reserva::factory()->count(5)->create();
        //Comentario::factory()->count(5)->create();
        Usuario::factory(10)->create();
        
        $this->call([
            
            
            EstablecimientoSeeder::class,
            UsuarioSeeder::class, 
            AsientosSeeder::class,
        
        ]);
        
         Evento::factory()->count(5)->create();
    }
}
