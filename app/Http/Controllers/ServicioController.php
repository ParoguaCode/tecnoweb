<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServicioController extends Controller
{
    // LISTAR SERVICIOS
    public function index()
    {
        return Inertia::render('Servicios/Index', [
            'servicios' => Servicio::orderBy('id', 'desc')->paginate(10)
        ]);
    }

    // FORM CREAR
    public function create()
    {
        return Inertia::render('Servicios/Create');
    }

    // GUARDAR
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'costo'       => 'required|numeric|min:0',
        ]);

        Servicio::create($data);

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio creado correctamente.');
    }

    // FORM EDITAR
    public function edit(Servicio $servicio)
    {
        return Inertia::render('Servicios/Edit', [
            'servicio' => $servicio
        ]);
    }

    // ACTUALIZAR
    public function update(Request $request, Servicio $servicio)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'costo'       => 'required|numeric|min:0',
        ]);

        $servicio->update($data);

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    // BORRADO LÓGICO
    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return back()->with('success', 'Servicio eliminado.');
    }
}
