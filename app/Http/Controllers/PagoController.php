<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\PlanPago;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PlanPago $planPago)
    {
        return Inertia::render('Pagos/Index', [
            'plan' => $planPago->load('ordenTrabajo'),
            'pagos' => $planPago->pagos()->orderBy('numerocuota')->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PlanPago $planPago)
    {
        $registrados = $planPago->pagos()->count();
        if ($registrados >= (int) $planPago->numerocuotas) {
            return redirect()->route('plan-pagos.show', $planPago->id)
                ->with('error', 'Ya se registraron todas las cuotas del plan.');
        }
        $siguienteCuota = $registrados + 1;
        return Inertia::render('Pagos/Create', [
            'plan' => $planPago->load(['ordenTrabajo']),
            'siguienteCuota' => $siguienteCuota,
            'metodos' => ['efectivo', 'tarjeta', 'pago facil'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PlanPago $planPago)
    {
        if ($planPago->pagos()->count() >= (int) $planPago->numerocuotas) {
            return redirect()->route('plan-pagos.show', $planPago->id)
                ->with('error', 'Ya se registraron todas las cuotas del plan.');
        }

        $data = $request->validate([
            'estado' => 'required|string|in:pendiente,en proceso,terminado',
            'fechapago' => 'required|date',
            'metodopago' => 'required|string|in:efectivo,tarjeta,pago facil',
            'monto' => 'required|numeric|min:0',
            'numerocuota' => 'required|integer|min:1|max:' . (int) $planPago->numerocuotas,
            'referencia' => 'nullable|string',
        ]);

        Pago::crearParaPlan($planPago, $data);

        $planPago->refresh()->actualizarEstadoSegunPagos();

        return redirect()->route('plan-pagos.show', $planPago->id)
            ->with('success', 'Pago registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        return Inertia::render('Pagos/Show', [
            'pago' => $pago->load('planPago'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        return Inertia::render('Pagos/Edit', [
            'pago' => $pago->load(['planPago', 'planPago.ordenTrabajo']),
            'metodos' => ['efectivo', 'tarjeta', 'pago facil'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        $maxCuotas = (int) optional($pago->planPago)->numerocuotas ?: 1;
        $data = $request->validate([
            'estado' => 'required|string|in:pendiente,en proceso,terminado',
            'fechapago' => 'required|date',
            'metodopago' => 'required|string|in:efectivo,tarjeta,pago facil',
            'monto' => 'required|numeric|min:0',
            'numerocuota' => 'required|integer|min:1|max:' . $maxCuotas,
            'referencia' => 'nullable|string',
        ]);

        $pago->actualizarDesdeFormulario($data);
        optional($pago->planPago)->actualizarEstadoSegunPagos();

        return redirect()->route('plan-pagos.show', $pago->plan_pago_id)
            ->with('success', 'Pago actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        $planId = $pago->plan_pago_id;
        $pago->delete();
        $plan = PlanPago::find($planId);
        if ($plan) {
            $plan->actualizarEstadoSegunPagos();
        }
        return redirect()->route('plan-pagos.show', $planId)
            ->with('success', 'Pago eliminado.');
    }
}
