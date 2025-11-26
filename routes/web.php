<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ModeloController;
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
require __DIR__.'/settings.php';
