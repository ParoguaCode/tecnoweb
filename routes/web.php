<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\ParteController;
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
Route::resource('clientes', ClienteController::class)->middleware(['auth', 'verified']);
Route::resource('partes', ParteController::class)->middleware(['auth', 'verified']);
Route::resource('motores', MotorController::class)
    ->parameters(['motores' => 'motor'])
    ->middleware(['auth', 'verified']);
    
Route::get('permisos', [PermisoController::class, 'index'])->middleware(['auth', 'verified'])->name('permisos.index');
// Route::post('permisos/actualizar', [PermisoController::class, 'actualizarPermisos'])->middleware(['auth', 'verified'])->name('permisos.actualizar');
Route::post('permisos/actualizar', [PermisoController::class, 'actualizarPermisos'])->middleware(['auth', 'verified'])->name('permisos.actualizar');


require __DIR__.'/settings.php';
