<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\OrdenTrabajoServicioController;
use App\Http\Controllers\IncidenciaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::resource('marcas', MarcaController::class)->middleware(['auth', 'verified']);
Route::resource('usuarios', UsuarioController::class)->middleware(['auth', 'verified']);

Route::resource('modelos', ModeloController::class)->middleware(['auth', 'verified']);

Route::get('permisos', [PermisoController::class, 'index'])->middleware(['auth', 'verified'])->name('permisos.index');
// Route::post('permisos/actualizar', [PermisoController::class, 'actualizarPermisos'])->middleware(['auth', 'verified'])->name('permisos.actualizar');
Route::post('permisos/actualizar', [PermisoController::class, 'actualizarPermisos'])->middleware(['auth', 'verified'])->name('permisos.actualizar');
Route::resource('orden-trabajos', OrdenTrabajoController::class)->middleware(['auth', 'verified']);
Route::resource('servicios', ServicioController::class)->middleware(['auth', 'verified']);


// Detalle servicios de la orden
Route::post('/orden-trabajos/{ordenTrabajo}/servicios', 
    [OrdenTrabajoServicioController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('orden-servicio.store');

Route::put('/orden-trabajo-servicio/{detalle}', 
    [OrdenTrabajoServicioController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('orden-servicio.update');

Route::delete('/orden-trabajo-servicio/{detalle}', 
    [OrdenTrabajoServicioController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('orden-servicio.destroy');

// Incidencias
Route::get('/orden-trabajos/{ordenTrabajo}/incidencias/create', 
    [IncidenciaController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('incidencias.create');

Route::post('/orden-trabajos/{ordenTrabajo}/incidencias', 
    [IncidenciaController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('incidencias.store');

Route::get('/incidencias/{incidencia}/edit', 
    [IncidenciaController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('incidencias.edit');

Route::put('/incidencias/{incidencia}', 
    [IncidenciaController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('incidencias.update');

Route::delete('/incidencias/{incidencia}', 
    [IncidenciaController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('incidencias.destroy');

require __DIR__.'/settings.php';
