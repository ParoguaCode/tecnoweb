<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Servicio;
use App\Models\OrdenTrabajoServicio;
use Illuminate\Http\Request;

class OrdenTrabajoServicioController extends Controller
{
    // AGREGAR SERVICIO A LA ORDEN
    public function store(Request $request, OrdenTrabajo $ordenTrabajo)
    {
        $data = $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'cantidad' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
        ]);

        $subtotal = $data['cantidad'] * $data['precio'];

        OrdenTrabajoServicio::create([
            'orden_trabajo_id' => $ordenTrabajo->id,
            'servicio_id'      => $data['servicio_id'],
            'cantidad'         => $data['cantidad'],
            'precio'           => $data['precio'],
            'subtotal'         => $subtotal,
        ]);

        // Recalcular total
        $this->recalcularTotal($ordenTrabajo);

        return back()->with('success', 'Servicio agregado a la orden.');
    }

    // ACTUALIZAR SERVICIO
    public function update(Request $request, OrdenTrabajoServicio $detalle)
    {
        $data = $request->validate([
            'cantidad' => 'required|integer|min:1',
            'precio'   => 'required|numeric|min:0',
        ]);

        $data['subtotal'] = $data['cantidad'] * $data['precio'];

        $detalle->update($data);

        $this->recalcularTotal($detalle->ordenTrabajo);

        return back()->with('success', 'Servicio actualizado.');
    }

    // ELIMINAR SERVICIO
    public function destroy(OrdenTrabajoServicio $detalle)
    {
        $orden = $detalle->ordenTrabajo;

        $detalle->delete();

        $this->recalcularTotal($orden);

        return back()->with('success', 'Servicio eliminado.');
    }

    // =====================================
    // RE-CALCULAR TOTAL DE LA ORDEN
    // =====================================
    private function recalcularTotal(OrdenTrabajo $orden)
    {
        // IMPORTANTE: tabla correcta = orden_trabajo_servicios
        $total = $orden->servicios()->sum('orden_trabajo_servicios.subtotal');

        $orden->update([
            'total' => $total
        ]);
    }
}
