<?php



namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard'; // o '/admin', etc.
    public function boot()
    {
        $this->routes(function () {
            // Carga las rutas del archivo api.php
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Carga las rutas del archivo web.php
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
