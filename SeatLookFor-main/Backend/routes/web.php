<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\EventoWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\PerfilController;
use App\Http\Controllers\Web\AsientoWebController;
use App\Http\Controllers\Web\ReservaWebController;

// ============================================================
// RUTAS USUARIO (Frontend público)
// ============================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/eventos', [EventoWebController::class, 'index'])->name('eventos.index');
Route::get('/evento/{id}', [EventoWebController::class, 'show'])->name('evento.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Auth de usuario (solo para invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthWebController::class, 'login'])->name('login.store');
    Route::get('/registro', [AuthWebController::class, 'showRegistro'])->name('registro');
    Route::post('/registro', [AuthWebController::class, 'registro'])->name('registro.store');
});

Route::post('/logout', [AuthWebController::class, 'logout'])->middleware('auth')->name('logout');

// Rutas protegidas de usuario
Route::middleware('auth')->group(function () {
    Route::get('/usuario', [PerfilController::class, 'index'])->name('usuario.perfil');
    Route::post('/usuario/password', [PerfilController::class, 'cambiarPassword'])->name('usuario.password');
    Route::post('/usuario/eliminar', [PerfilController::class, 'eliminarCuenta'])->name('usuario.eliminar');
    Route::get('/asientos/{idEvento}', [AsientoWebController::class, 'index'])->name('asientos.index');
    Route::post('/asientos/seleccionar', [AsientoWebController::class, 'seleccionar'])->name('asientos.seleccionar');
    Route::get('/resumen/{idEvento}', [ReservaWebController::class, 'resumen'])->name('reserva.resumen');
    Route::post('/reserva', [ReservaWebController::class, 'crear'])->name('reserva.crear');
    Route::post('/reserva/liberar', [ReservaWebController::class, 'liberar'])->name('reserva.liberar');
    Route::post('/asientos/{id}/comentar', [AsientoWebController::class, 'comentar'])->name('asientos.comentar');
    Route::post('/eventos/{id}/comentar', [AsientoWebController::class, 'comentarEvento'])->name('eventos.comentar');
    Route::post('/comentarios/{id}/responder', [AsientoWebController::class, 'responder'])->name('comentarios.responder');
    Route::delete('/comentarios/{id}', [AsientoWebController::class, 'eliminarComentario'])->name('comentarios.eliminar');
});

// ============================================================
// RUTAS ADMINISTRACIÓN (Prefijo /admin)
// ============================================================

// El login de admin ahora es el mismo que el de usuario
Route::get('/admin/login', fn () => redirect()->route('login'))
    ->middleware('guest')
    ->name('admin.login.form');

Route::post('/admin/login', [AuthenticatedSessionController::class, 'logueoBack'])
    ->middleware('guest')
    ->name('admin.login');

Route::post('/admin/logout', [AuthenticatedSessionController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Establecimientos
    Route::get('/establecimientos', [EstablecimientoController::class, 'listar'])->name('establecimiento.listado');
    Route::get('/establecimientos/crear', fn () => view('Establecimiento.Crear'))->name('establecimientos.crear');
    Route::post('/establecimientos/guardar', [EstablecimientoController::class, 'guardar'])->name('establecimiento.guardar');
    Route::post('/establecimientos/asientos', [EstablecimientoController::class, 'guardarAsientos'])->name('establecimientos.asientos.guardar');
    Route::get('/establecimientos/{idEst}', [EstablecimientoController::class, 'mostrar'])->name('establecimiento.mostrar');
    Route::post('/establecimientos/eliminar/{id}', [EstablecimientoController::class, 'eliminar'])->name('establecimiento.eliminar');

    // Eventos
    Route::get('/eventos', [EventoController::class, 'listar'])->name('eventos.listado');
    Route::get('/eventos/crear', [EventoController::class, 'formularioCrear'])->name('eventos.crear');
    Route::post('/eventos/guardar', [EventoController::class, 'guardar'])->name('eventos.guardar');
    Route::get('/eventos/ver/{idEve}', [EventoController::class, 'ver'])->name('eventos.ver');
    Route::get('/zonas-por-establecimiento/{idEst}', [EventoController::class, 'obtenerZonas'])->name('zonas.porEstablecimiento');
    Route::post('/eventos/eliminar/{id}', [EventoController::class, 'eliminar'])->name('eventos.eliminar');
    Route::post('/eventos/estado/{id}', [EventoController::class, 'cambiarEstado'])->name('eventos.estado');
});
