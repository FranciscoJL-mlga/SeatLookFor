<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuario')->insert([
            [
                'nombre'   => 'Francisco',
                'apellido' => 'jimenez',
                'email'    => 'paco@seatlookfor.com',
                'password' => Hash::make('password123'),
                'estado'   => true,
                'admin'    => true,
            ],
            [
                'nombre'   => 'Antonio',
                'apellido' => 'Heredias',
                'email'    => 'toni@seatlookfor.com',
                'password' => Hash::make('password123'),
                'estado'   => true,
                'admin'    => false,
            ],
            [
                'nombre'   => 'María',
                'apellido' => 'García Sánchez',
                'email'    => 'maria.garcia@gmail.com',
                'password' => Hash::make('password123'),
                'estado'   => true,
                'admin'    => false,
            ],
            [
                'nombre'   => 'Carlos',
                'apellido' => 'Martínez Ruiz',
                'email'    => 'carlos.martinez@gmail.com',
                'password' => Hash::make('password123'),
                'estado'   => true,
                'admin'    => false,
            ],
            [
                'nombre'   => 'Laura',
                'apellido' => 'Sánchez Pérez',
                'email'    => 'laura.sanchez@hotmail.com',
                'password' => Hash::make('password123'),
                'estado'   => true,
                'admin'    => false,
            ],
            [
                'nombre'   => 'Javier',
                'apellido' => 'López Fernández',
                'email'    => 'javier.lopez@yahoo.es',
                'password' => Hash::make('password123'),
                'estado'   => true,
                'admin'    => false,
            ],
            [
                'nombre'   => 'Ana',
                'apellido' => 'Fernández Gómez',
                'email'    => 'ana.fernandez@gmail.com',
                'password' => Hash::make('password123'),
                'estado'   => true,
                'admin'    => false,
            ],
            [
                'nombre'   => 'Pedro',
                'apellido' => 'González Torres',
                'email'    => 'pedro.gonzalez@outlook.com',
                'password' => Hash::make('password123'),
                'estado'   => true,
                'admin'    => false,
            ],
        ]);
    }
}
