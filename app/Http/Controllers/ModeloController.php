<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    public function index()
    {
        $modelos = Modelo::orderBy('id', 'desc')->get();
        return inertia('Modelos/Index', compact('modelos'));
    }

    public function create()
    {
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
        $modelo->delete();

        return redirect()->route('modelos.index')->with('success', 'Modelo eliminado correctamente.');
    }
}
