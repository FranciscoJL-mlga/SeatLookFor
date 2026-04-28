<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\DemoSeeder;

class DemoReset extends Command
{
    protected $signature = 'demo:reset';
    protected $description = 'Elimina todo el contenido demo y lo vuelve a crear desde cero';

    public function handle(): void
    {
        $this->info('Reseteando contenido demo...');

        // IDs de establecimientos y eventos demo
        $demoEstIds = DB::table('establecimiento')->where('demo', true)->pluck('idEst');
        $demoEveIds = DB::table('evento')->where('demo', true)->pluck('idEve');
        $demoAsiIds = DB::table('asiento')->whereIn('idEst', $demoEstIds)->pluck('idAsi');

        // Borrar en orden para respetar FK
        DB::table('reserva')->whereIn('idEve', $demoEveIds)->delete();
        DB::table('asientos_evento')->whereIn('idEve', $demoEveIds)->delete();
        DB::table('evento')->where('demo', true)->delete();
        DB::table('comentario')->whereIn('idAsi', $demoAsiIds)->delete();
        DB::table('asiento')->whereIn('idAsi', $demoAsiIds)->delete();
        DB::table('zona')->whereIn('idEst', $demoEstIds)->delete();
        DB::table('establecimiento')->where('demo', true)->delete();

        // Resetear contraseña del usuario demo (por si la cambiaron)
        DB::table('usuario')->where('es_demo', true)->update([
            'password' => Hash::make('demo1234'),
            'estado'   => true,
        ]);

        // Volver a sembrar contenido demo
        (new DemoSeeder())->run();

        $this->info('Contenido demo reseteado correctamente.');
    }
}
