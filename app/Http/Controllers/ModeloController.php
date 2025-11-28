<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    public function index(Request $request)
    {
        if (!request()->user()->tienePermiso('modelo.listar')) {
            return redirect()->route('dashboard')->with('error', 'No tenés permiso para listar modelos.');
        }

        return inertia('Modelos/Index', [
            'modelos' => Modelo::query()
            ->when(
                $request->busqueda,
                function ($query) use ($request) {
                    $query->whereRaw('LOWER(nombre) like LOWER(?)', ['%' . $request->busqueda . '%']);
                }
            )
            ->orderBy('id', 'desc')
            ->paginate(5)
            ->withQueryString(),

            'terminosBusqueda' => $request->busqueda
        ]);
    }

    public function create()
    {
        if (!request()->user()->tienePermiso('modelo.crear')) {
            return redirect()->route('dashboard')->with('error', 'No tenés permiso para crear modelos.');
        }

        return inertia('Modelos/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Modelo::create($data);

        return redirect()->route('modelos.index')->with('success', 'Modelo creado correctamente.');
    }

    public function edit(Modelo $modelo)
    {
        if (!request()->user()->tienePermiso('modelo.editar')) {
            return redirect()->route('dashboard')->with('error', 'No tenés permiso para editar modelos.');
        }

        return inertia('Modelos/Edit', compact('modelo'));
    }

    public function update(Request $request, Modelo $modelo)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $modelo->update($data);

        return redirect()->route('modelos.index')->with('success', 'Modelo actualizado correctamente.');
    }

    public function destroy(Modelo $modelo)
    {
        if ($modelo->motores()->count() > 0) {
            return redirect()->route('modelos.index')->with('error', 'No se puede eliminar el modelo porque tiene motores asociados.');
        }

        $modelo->delete();

        return redirect()->route('modelos.index')->with('success', 'Modelo eliminado correctamente.');
    }
}
