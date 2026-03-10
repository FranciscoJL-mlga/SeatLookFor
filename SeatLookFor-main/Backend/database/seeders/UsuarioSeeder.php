<?php
namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
         DB::table('usuario')->insert([
            [
                'nombre' => 'Francisco',
                'apellido' => 'Jimenez',
                'email' => 'paco@paco.com',
                'password' => Hash::make('1234'),
                'estado' => true,
                'admin' => true,
            ],
            [
                'nombre' => 'Antonio',
                'apellido' => 'Heredias',
                'email' => 'toni@toni.com',
                'password' => Hash::make('1234'),
                'estado' => true,
                'admin' => false,
            ],
        ]);
    }
}
