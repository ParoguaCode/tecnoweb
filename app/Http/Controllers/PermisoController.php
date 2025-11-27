<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Http\Request;

class PermisoController extends Controller
{

    public function index()
    {
        $roles = Rol::with('permisos')->get()->map(function ($rol) {
            return [
                'id' => $rol->id,
                'nombre' => $rol->nombre,
                'descripcion' => $rol->descripcion,
                'permisos' => $rol->permisos->pluck('id')->toArray(),
            ];
        });
        
        $permisosSistema = Permiso::all();

        return inertia('Permisos/Index', [
            'roles' => $roles,
            'permisos' => $permisosSistema,
        ]);
    }

    public function actualizarPermisos(Request $request)
    {
        $request->validate([
            'rol_id' => 'required|exists:roles,id',
            'permisos' => 'required|array',
            'permisos.*' => 'exists:permisos,id',
        ]);

        $rol = Rol::findOrFail($request->rol_id);
        
        // Sincronizar los permisos del rol
        $rol->permisos()->sync($request->permisos);

        return redirect()->back()->with('success', 'Permisos actualizados correctamente');
    }

}
