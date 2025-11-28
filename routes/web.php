<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\ParteController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\PlanPagoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\FacturaController;
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
Route::resource('clientes', ClienteController::class)->middleware(['auth', 'verified']);
Route::resource('partes', ParteController::class)->middleware(['auth', 'verified']);
Route::resource('motores', MotorController::class)
    ->parameters(['motores' => 'motor'])
    ->middleware(['auth', 'verified']);
    
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

// Plan de Pagos
Route::get('plan-pagos', [PlanPagoController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('plan-pagos.index');

Route::get('/plan-pagos/{planPago}', [PlanPagoController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('plan-pagos.show');

Route::get('/orden-trabajos/{ordenTrabajo}/plan-pago/create', [PlanPagoController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('plan-pagos.create');

Route::post('/orden-trabajos/{ordenTrabajo}/plan-pago', [PlanPagoController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('plan-pagos.store');

Route::get('/plan-pagos/{planPago}/edit', [PlanPagoController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('plan-pagos.edit');

Route::put('/plan-pagos/{planPago}', [PlanPagoController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('plan-pagos.update');

Route::delete('/plan-pagos/{planPago}', [PlanPagoController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('plan-pagos.destroy');

// Pagos
Route::get('/plan-pagos/{planPago}/pagos', [PagoController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('pagos.index');
Route::get('/plan-pagos/{planPago}/pagos/create', [PagoController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('pagos.create');
Route::post('/plan-pagos/{planPago}/pagos', [PagoController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('pagos.store');
Route::get('/pagos/{pago}', [PagoController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('pagos.show');
Route::get('/pagos/{pago}/edit', [PagoController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('pagos.edit');
Route::put('/pagos/{pago}', [PagoController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('pagos.update');
Route::delete('/pagos/{pago}', [PagoController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('pagos.destroy');

// PagoFacil
Route::get('/pagofacil/login', [PagoController::class, 'pagofacilLogin'])
    ->middleware(['auth', 'verified'])
    ->name('pagofacil.login');

Route::get('/pagofacil/list-enabled-services', [PagoController::class, 'pagofacilListEnabledServices'])
    ->middleware(['auth', 'verified'])
    ->name('pagofacil.list');

Route::post('/pagofacil/generate-qr', [PagoController::class, 'pagofacilGenerateQr'])
    ->middleware(['auth', 'verified'])
    ->name('pagofacil.generate');

Route::post('/pagofacil/callback', [PagoController::class, 'pagofacilCallback'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
    ->name('pagofacil.callback');

Route::post('/webhooks/pagofacil', [PagoController::class, 'pagofacilCallback'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
    ->name('pagofacil.callback.webhook');

Route::get('/pagofacil/callback-url', [PagoController::class, 'pagofacilCallbackUrl'])
    ->middleware(['auth', 'verified'])
    ->name('pagofacil.callback-url');

Route::post('/pagofacil/query-transaction', [PagoController::class, 'pagofacilQueryTransaction'])
    ->middleware(['auth', 'verified'])
    ->name('pagofacil.query');

// Facturas
Route::get('/pagos/{pago}/factura/create', [FacturaController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('facturas.create');
Route::post('/pagos/{pago}/factura', [FacturaController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('facturas.store');
Route::get('/facturas/{factura}', [FacturaController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('facturas.show');

require __DIR__.'/settings.php';
