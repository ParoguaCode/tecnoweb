<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Motor;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrdenTrabajoController extends Controller
{
    public function index()
    {
        return Inertia::render('OrdenTrabajo/Index', [
            'ordenes' => OrdenTrabajo::with(['cliente', 'usuario', 'motor'])
                ->orderBy('id', 'desc')
                ->paginate(10)
        ]);
    }

    public function create()
    {
        return Inertia::render('OrdenTrabajo/Create', [
            // ✔ Cliente en formato para ComboBox
            'clientes' => Cliente::select('id', 'nombre')
                ->get()
                ->map(fn($c) => [
                    'id' => $c->id,
                    'label' => $c->nombre
                ]),

            // ✔ Usuarios en formato ComboBox
            'usuarios' => User::select('id', 'name')
                ->get()
                ->map(fn($u) => [
                    'id' => $u->id,
                    'label' => $u->name
                ]),

            // ✔ Motores mostrando Marca + Modelo + Número de Serie
            'motores' => Motor::with(['marca', 'modelo'])
                ->get()
                ->map(fn($m) => [
                    'id' => $m->id,
                    'label' => "{$m->marca->nombre} - {$m->modelo->nombre} ({$m->numero_serie})"
                ]),

            'estados' => ['pendiente','aprobado','proceso','terminado'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fechainicio' => 'required|date',
            'fechafin' => 'nullable|date',
            'descripcion' => 'required|string',
            'total' => 'sometimes|numeric|min:0',
            'estado' => 'required|in:pendiente,aprobado,proceso,terminado',
            'cliente_id' => 'required|exists:clientes,id',
            'usuario_id' => 'required|exists:users,id',
            'motor_id' => 'required|exists:motores,id',
        ]);

        $data['total'] = 0;
        OrdenTrabajo::create($data);

        return redirect()->route('orden-trabajos.index')
            ->with('success', 'Orden creada correctamente.');
    }

    public function edit(OrdenTrabajo $ordenTrabajo)
    {
        return Inertia::render('OrdenTrabajo/Edit', [
            'orden' => $ordenTrabajo->load(['cliente', 'usuario', 'motor']),

            // ✔ mismo formato que create()
            'clientes' => Cliente::select('id', 'nombre')
                ->get()
                ->map(fn($c) => [
                    'id' => $c->id,
                    'label' => $c->nombre
                ]),

            'usuarios' => User::select('id', 'name')
                ->get()
                ->map(fn($u) => [
                    'id' => $u->id,
                    'label' => $u->name
                ]),

            'motores' => Motor::with(['marca', 'modelo'])
                ->get()
                ->map(fn($m) => [
                    'id' => $m->id,
                    'label' => "{$m->marca->nombre} - {$m->modelo->nombre} ({$m->numero_serie})"
                ]),

            'estados' => ['pendiente','aprobado','proceso','terminado'],
        ]);
    }

    public function update(Request $request, OrdenTrabajo $ordenTrabajo)
    {
        $data = $request->validate([
            'fechainicio' => 'required|date',
            'fechafin' => 'nullable|date',
            'descripcion' => 'required|string',
            'total' => 'required|numeric',
            'estado' => 'required',
            'cliente_id' => 'required|exists:clientes,id',
            'usuario_id' => 'required|exists:users,id',
            'motor_id' => 'required|exists:motores,id',
        ]);

        $ordenTrabajo->update($data);

        return back()->with('success', 'Orden actualizada.');
    }
    public function show(OrdenTrabajo $ordenTrabajo)
    {
        $ordenTrabajo->load([
           'cliente',
           'usuario',
           'motor',
           'servicios' => function ($q) {
               $q->withPivot(['cantidad', 'precio', 'subtotal']);
            },
           'incidencias',
           'planPago',
        ]);

         $serviciosCatalogo = Servicio::select('id', 'nombre', 'costo')
           ->orderBy('nombre')
           ->get();

        return Inertia::render('OrdenTrabajo/Show', [
          'orden' => $ordenTrabajo,
           'serviciosCatalogo' => $serviciosCatalogo,
        ]);
    }

    public function destroy(OrdenTrabajo $ordenTrabajo)
    {
        $ordenTrabajo->delete();

        return back()->with('success', 'Orden eliminada.');
    }
}
