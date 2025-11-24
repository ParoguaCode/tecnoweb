<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::orderBy('id', 'desc')->get();
        return inertia('Marcas/Index', compact('marcas'));
    }

    public function create()
    {
        return inertia('Marcas/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Marca::create($data);

        return redirect()->route('marcas.index')->with('success', 'Marca creada correctamente.');
    }

    public function edit(Marca $marca)
    {
        return inertia('Marcas/Edit', compact('marca'));
    }

    public function update(Request $request, Marca $marca)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $marca->update($data);

        return redirect()->route('marcas.index')->with('success', 'Marca actualizada correctamente.');
    }

    public function destroy(Marca $marca)
    {
        $marca->delete();

        return redirect()->route('marcas.index')->with('success', 'Marca eliminada correctamente.');
    }
}
