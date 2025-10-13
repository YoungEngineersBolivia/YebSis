<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\Persona;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfesoresController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $profesores = Profesor::with(['persona.rol', 'usuario'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('persona', function ($p) use ($search) {
                    $p->where('Nombre', 'ilike', "%{$search}%")
                      ->orWhere('Apellido', 'ilike', "%{$search}%");
                })
                ->orWhereHas('usuario', function ($u) use ($search) {
                    $u->where('Correo', 'ilike', "%{$search}%");
                });
            })
            ->orderBy('Id_profesores', 'desc')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('administrador.profesoresAdministrador', compact('profesores', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'genero' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'celular' => 'nullable|string|max:50',
            'id_roles' => 'required|integer|exists:roles,Id_roles',
            'correo' => 'required|email|max:255|unique:usuarios,Correo',
            'contrasenia' => 'required|string|min:6',
            'profesion' => 'nullable|string|max:255',
            'rol_componentes' => ['nullable', Rule::in(['Tecnico', 'Inventario', 'Ninguno'])],
        ]);

        return DB::transaction(function () use ($validated) {
            $persona = Persona::create([
                'Nombre' => $validated['nombre'],
                'Apellido' => $validated['apellido'],
                'Genero' => $validated['genero'] ?? null,
                'Direccion_domicilio' => $validated['direccion'] ?? null,
                'Fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                'Fecha_registro' => now()->toDateString(),
                'Celular' => $validated['celular'] ?? null,
                'Id_roles' => $validated['id_roles'],
            ]);

            $usuario = Usuario::create([
                'Correo' => $validated['correo'],
                'Contrasenia' => Hash::make($validated['contrasenia']),
                'Id_personas' => $persona->Id_personas,
            ]);

            $profesor = Profesor::create([
                'Profesion' => $validated['profesion'] ?? null,
                'Rol_componentes' => $validated['rol_componentes'] ?? 'Ninguno',
                'Id_personas' => $persona->Id_personas,
                'Id_usuarios' => $usuario->Id_usuarios,
            ]);

            return redirect()->route('profesores.index')->with('success', 'Profesor registrado correctamente.');
        });
    }

    public function show($id)
    {
        $profesor = Profesor::with(['persona.rol', 'usuario'])->findOrFail($id);

        return response()->json([
            'Id_profesores' => $profesor->Id_profesores,
            'Profesion' => $profesor->Profesion,
            'Rol_componentes' => $profesor->Rol_componentes,
            'Persona' => [
                'Nombre' => $profesor->persona->Nombre ?? '',
                'Apellido' => $profesor->persona->Apellido ?? '',
                'Genero' => $profesor->persona->Genero ?? '',
                'Celular' => $profesor->persona->Celular ?? '',
                'Rol' => $profesor->persona->rol->Nombre_rol ?? '',
            ],
            'Usuario' => [
                'Correo' => $profesor->usuario->Correo ?? '',
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $profesor = Profesor::with(['persona', 'usuario'])->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'genero' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'celular' => 'nullable|string|max:50',
            'id_roles' => 'required|integer|exists:roles,Id_roles',
            'correo' => [
                'required', 'email', 'max:255',
                Rule::unique('usuarios', 'Correo')->ignore($profesor->usuario->Id_usuarios, 'Id_usuarios'),
            ],
            'contrasenia' => 'nullable|string|min:6',
            'profesion' => 'nullable|string|max:255',
            'rol_componentes' => ['nullable', Rule::in(['Tecnico', 'Inventario', 'Ninguno'])],
        ]);

        return DB::transaction(function () use ($validated, $profesor) {
            $profesor->persona->update([
                'Nombre' => $validated['nombre'],
                'Apellido' => $validated['apellido'],
                'Genero' => $validated['genero'] ?? null,
                'Direccion_domicilio' => $validated['direccion'] ?? null,
                'Fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                'Celular' => $validated['celular'] ?? null,
                'Id_roles' => $validated['id_roles'],
            ]);

            $profesor->usuario->update([
                'Correo' => $validated['correo'],
                'Contrasenia' => !empty($validated['contrasenia'])
                    ? Hash::make($validated['contrasenia'])
                    : $profesor->usuario->Contrasenia,
            ]);

            $profesor->update([
                'Profesion' => $validated['profesion'] ?? null,
                'Rol_componentes' => $validated['rol_componentes'] ?? 'Ninguno',
            ]);

            return redirect()->route('profesores.index')->with('success', 'Profesor actualizado correctamente.');
        });
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $profesor = Profesor::findOrFail($id);
            $profesor->delete();
            DB::commit();

            return redirect()->route('profesores.index')->with('success', 'Profesor eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('profesores.index')->with('error', $e->getMessage());
        }
    }
}
