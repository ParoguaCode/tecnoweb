<?php

namespace App\Http\Controllers;

use App\Models\PlanPago;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanPagoController extends Controller
{
    public function index()
    {
        return Inertia::render('PlanPagos/Index', [
            'planes' => PlanPago::with(['ordenTrabajo'])
                ->orderBy('id', 'desc')
                ->paginate(10),
        ]);
    }

    public function show(PlanPago $planPago)
    {
        $planPago->load(['ordenTrabajo', 'pagos' => function ($q) {
            $q->orderBy('numerocuota');
        }, 'pagos.factura']);

        return Inertia::render('PlanPagos/Show', [
            'plan' => $planPago,
        ]);
    }

    public function create(OrdenTrabajo $ordenTrabajo)
    {
        if ($ordenTrabajo->planPago) {
            return redirect()->route('orden-trabajos.show', $ordenTrabajo->id)
                ->with('error', 'La orden ya tiene un plan de pagos.');
        }

        return Inertia::render('PlanPagos/Create', [
            'orden' => $ordenTrabajo,
        ]);
    }

    public function store(Request $request, OrdenTrabajo $ordenTrabajo)
    {
        if ($ordenTrabajo->planPago) {
            return redirect()->route('orden-trabajos.show', $ordenTrabajo->id)
                ->with('error', 'La orden ya tiene un plan de pagos.');
        }

        $data = $request->validate([
            'estado' => 'required|string',
            'fechainicio' => 'required|date',
            'fechafin' => 'nullable|date',
            'montoporcuota' => 'nullable|numeric|min:0',
            'numerocuotas' => 'required|integer|min:1',
            'observacion' => 'nullable|string',
        ]);

        PlanPago::crearParaOrden($ordenTrabajo, $data);

        return redirect()->route('plan-pagos.index')
            ->with('success', 'Plan de pagos creado correctamente.');
    }

    public function edit(PlanPago $planPago)
    {
        $planPago->load('ordenTrabajo');
        return Inertia::render('PlanPagos/Edit', [
            'plan' => $planPago,
        ]);
    }

    public function update(Request $request, PlanPago $planPago)
    {
        $data = $request->validate([
            'estado' => 'required|string',
            'fechainicio' => 'required|date',
            'fechafin' => 'nullable|date',
            'montoporcuota' => 'nullable|numeric|min:0',
            'numerocuotas' => 'required|integer|min:1',
            'observacion' => 'nullable|string',
        ]);

        $planPago->load('ordenTrabajo');
        $planPago->actualizarDesdeFormulario($data);

        return redirect()->route('plan-pagos.index')
            ->with('success', 'Plan de pagos actualizado.');
    }

    public function destroy(PlanPago $planPago)
    {
        $planPago->delete();
        return redirect()->route('plan-pagos.index')
            ->with('success', 'Plan de pagos eliminado.');
    }
}
