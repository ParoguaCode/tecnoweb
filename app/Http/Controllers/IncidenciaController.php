<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncidenciaController extends Controller
{
    // 🔹 LISTA OPCIONAL (NO necesario para tu flujo actual)
    public function index()
    {
        return Inertia::render('Incidencias/Index', [
            'incidencias' => Incidencia::with('ordenTrabajo')
                ->orderBy('id', 'desc')
                ->paginate(10)
        ]);
    }

    // 🔹 FORMULARIO DE CREACIÓN PARA UNA ORDEN
    public function create(OrdenTrabajo $ordenTrabajo)
    {
        return Inertia::render('Incidencias/Create', [
            'orden' => $ordenTrabajo
        ]);
    }

    // 🔹 GUARDAR INCIDENCIA
    public function store(Request $request, OrdenTrabajo $ordenTrabajo)
    {
        $data = $request->validate([
            'descripcion' => 'required|string|max:500',
            'fecha'       => 'nullable|date',
            // enum in DB: 'registrada', 'en revisión', 'resuelta'
            'estado'      => 'required|in:registrada,en revisión,resuelta',
        ]);

        // Si la incidencia se marca como resuelta, poner fecha automática si está vacía
        if ($data['estado'] === 'resuelto' && empty($data['fecha'])) {
            $data['fecha'] = now()->toDateString();
        }

        $data['orden_trabajo_id'] = $ordenTrabajo->id;

        Incidencia::create($data);

        // Después de crear → volver al Show de la orden
        return redirect()->route('orden-trabajos.show', $ordenTrabajo->id)
            ->with('success', 'Incidencia registrada correctamente.');
    }

    // 🔹 FORM EDITAR
    public function edit(Incidencia $incidencia)
    {
        return Inertia::render('Incidencias/Edit', [
            'incidencia' => $incidencia,
            'orden'      => $incidencia->ordenTrabajo,
        ]);
    }

    // 🔹 ACTUALIZAR
    public function update(Request $request, Incidencia $incidencia)
    {
        $data = $request->validate([
            'descripcion' => 'required|string|max:500',
            'fecha'       => 'nullable|date',
            // enum in DB: 'registrada', 'en revisión', 'resuelta'
            'estado'      => 'required|in:registrada,en revisión,resuelta',
        ]);

        // Si se marca como resuelta → asignar fecha automática si vacía
        if ($data['estado'] === 'resuelto' && empty($data['fecha'])) {
            $data['fecha'] = now()->toDateString();
        }

        $incidencia->update($data);

        // Volver al Show de la orden
        return redirect()->route('orden-trabajos.show', $incidencia->orden_trabajo_id)
            ->with('success', 'Incidencia actualizada correctamente.');
    }

    // 🔹 BORRADO
    public function destroy(Incidencia $incidencia)
    {
        $ordenId = $incidencia->orden_trabajo_id;

        $incidencia->delete();

        return redirect()->route('orden-trabajos.show', $ordenId)
            ->with('success', 'Incidencia eliminada correctamente.');
    }
}
