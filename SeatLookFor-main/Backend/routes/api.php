<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ApiAuthenticationController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

// Ruta de prueba
Route::get('/test', fn () => response()->json(['message' => 'API funcionando']));

// Si usas cookies con frontend tipo SPA (Vue/React)
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

// Rutas públicas de eventos
Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/eventos/{id}', [EventoController::class, 'mostrar']);
Route::get('/eventos/{id}/comentarios', [EventoController::class, 'comentariosPorEvento']);
Route::get('/recientes/{id}', [EventoController::class, 'recientes']);
Route::get('/zonas-por-establecimiento/{idEst}', [EventoController::class, 'obtenerZonas']);

// Rutas protegidas
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/asientos/{id}/comentar', [ComentarioController::class, 'comentar']);
    Route::get('/asientos/{id}/comentarios', [ComentarioController::class, 'getComentarios']);

    Route::post('/reservas', [ReservaController::class, 'crearReserva']);
});

Route::post('/register', [ApiAuthenticationController::class, 'register']);
Route::post('/login', [ApiAuthenticationController::class, 'login']);
Route::post('/logout', [ApiAuthenticationController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [ApiAuthenticationController::class, 'user'])->middleware('auth:sanctum');
