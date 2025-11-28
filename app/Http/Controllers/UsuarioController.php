<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Usuarios/Index', [
            'usuarios' => User::with([
                'rol',
            ])
                ->when($request->busqueda, function ($query) use ($request) {
                    $query->where(function ($subQuery) use ($request) {
                        $subQuery
                            ->where('name', 'LIKE', '%' . $request->busqueda . '%')
                            ->orWhere('email', 'LIKE', '%' . $request->busqueda . '%')
                            ->orWhereHas('rol', function ($subQuery) use ($request) {
                                $subQuery->where('nombre', 'LIKE', '%' . $request->busqueda . '%');
                            });
                    });
                })
                ->orderBy('name', 'asc')
                ->paginate(10)
                ->withQueryString(),

            'terminosBusqueda' => $request->busqueda
        ]);
    }

    public function create()
    {
        // $roles = Rol::where('id', '!=', 1)->get();
        $roles = Rol::all();
        return inertia('Usuarios/Create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name|max:255',
            'email' => 'required|unique:users,email|max:255', //TODO
            'password' => 'required|max:255|min:8',
            'telefono' => 'nullable|integer|digits_between:8,15',
            'direccion' => 'nullable|string|max:255',
            'foto' => 'nullable|image|max:2048',
            // 'tipo' => 'required|string|max:50',
            'rol_id' => 'required|exists:roles,id',
        ]);

        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('usuarios', 'public');
        }

        User::create($data);
        return redirect()->route('usuarios.index')->with('success', 'Usuario ' . $request->name . ' creado');
    }

    public function edit(User $usuario)
    {
        // $roles = Rol::where('id', '!=', $usuario->rol_id)->get();
        $roles = Rol::all();
        return inertia('Usuarios/Edit', [
            'usuario' => $usuario->load('rol'),
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|max:255|unique:users,name,' . $usuario->id,
            'email' => 'required|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|max:255|min:8',
            'telefono' => 'nullable|integer|digits_between:8,15',
            'direccion' => 'nullable|string|max:255',
            'foto' => 'nullable|image|max:2048',
            // 'tipo' => 'required|string|max:50',
            'rol_id' => 'required|exists:roles,id',
        ]);

        $data = $request->except(['password', 'foto']);
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('usuarios', 'public');
        }

        $usuario->update($data);
        return redirect()->route('usuarios.index')->with('success', 'Usuario ' . $usuario->name . ' actualizado');
    }

    public function destroy(User $usuario)
    {
        $nombre = $usuario->name;
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario ' . $nombre . ' eliminado');
    }
}
