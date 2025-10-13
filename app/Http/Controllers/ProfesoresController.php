<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Rol;
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
            // Persona
            'nombre'            => ['required','string','max:255'],
            'apellido'          => ['required','string','max:255'],
            'genero'            => ['nullable','string','max:50'],
            'direccion'         => ['nullable','string','max:255'],
            'fecha_nacimiento'  => ['nullable','date'],
            'celular'           => ['nullable','string','max:50'],
            'id_roles'          => ['required','integer','exists:roles,Id_roles'],

            // Usuario
            'correo'            => ['required','email','max:255','unique:usuarios,Correo'],
            'contrasenia'       => ['required','string','min:6'],

            // Profesor
            'profesion'         => ['nullable','string','max:255'],
            'rol_componentes'   => ['nullable','string','max:255'], 
        ]);

        return DB::transaction(function () use ($validated) {
            // Crear Persona
            $persona = Persona::create([
                'Nombre'            => $validated['nombre'],
                'Apellido'          => $validated['apellido'],
                'Genero'            => $validated['genero'] ?? null,
                'Direccion_domicilio'=> $validated['direccion'] ?? null,
                'Fecha_nacimiento'  => $validated['fecha_nacimiento'] ?? null,
                'Fecha_registro'    => now()->toDateString(),
                'Celular'           => $validated['celular'] ?? null,
                'Id_roles'          => $validated['id_roles'],
            ]);

            // Crear Usuario
            $usuario = Usuario::create([
                'Correo'        => $validated['correo'],
                'Contrasenia'   => Hash::make($validated['contrasenia']),
                'Id_personas'   => $persona->Id_personas,
            ]);

            // Crear Profesor
            $profesor = Profesor::create([
                'Profesion'         => $validated['profesion'] ?? null,
                'Rol_componentes'   => $validated['rol_componentes'] ?? null, // NUEVO CAMPO
                'Id_personas'       => $persona->Id_personas,
                'Id_usuarios'       => $usuario->Id_usuarios,
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Profesor creado correctamente.',
                'id'       => $profesor->Id_profesores,
            ], 201);
        });
    }

    public function show($id)
    {
        $profesor = Profesor::with(['persona.rol', 'usuario'])->findOrFail($id);

        return response()->json([
            'Id_profesores'  => $profesor->Id_profesores,
            'Profesion'      => $profesor->Profesion,
            'Rol_componentes'=> $profesor->Rol_componentes,
            'Persona'        => [
                'Nombre'    => $profesor->persona->Nombre ?? '',
                'Apellido'  => $profesor->persona->Apellido ?? '',
                'Genero'    => $profesor->persona->Genero ?? '',
                'Celular'   => $profesor->persona->Celular ?? '',
                'Rol'       => $profesor->persona->rol->Nombre_rol ?? '',
            ],
            'Usuario'        => [
                'Correo'    => $profesor->usuario->Correo ?? '',
            ],
        ]);
    }

    public function edit($id)
    {
        $profesor = Profesor::with(['persona.rol', 'usuario'])->findOrFail($id);

        return response()->json([
            'Id_profesores'   => $profesor->Id_profesores,
            'profesion'       => $profesor->Profesion,
            'rol_componentes' => $profesor->Rol_componentes,
            'nombre'          => $profesor->persona->Nombre ?? '',
            'apellido'        => $profesor->persona->Apellido ?? '',
            'genero'          => $profesor->persona->Genero ?? '',
            'direccion'       => $profesor->persona->Direccion_domicilio ?? '',
            'fecha_nacimiento'=> $profesor->persona->Fecha_nacimiento ?? '',
            'celular'         => $profesor->persona->Celular ?? '',
            'id_roles'        => $profesor->persona->Id_roles ?? null,
            'correo'          => $profesor->usuario->Correo ?? '',
        ]);
    }

    public function update(Request $request, $id)
    {
        $profesor = Profesor::with(['persona','usuario'])->findOrFail($id);

        $validated = $request->validate([
            // Persona
            'nombre'            => ['required','string','max:255'],
            'apellido'          => ['required','string','max:255'],
            'genero'            => ['nullable','string','max:50'],
            'direccion'         => ['nullable','string','max:255'],
            'fecha_nacimiento'  => ['nullable','date'],
            'celular'           => ['nullable','string','max:50'],
            'id_roles'          => ['required','integer','exists:roles,Id_roles'],

            'correo'            => [
                'required','email','max:255',
                Rule::unique('usuarios','Correo')->ignore($profesor->usuario->Id_usuarios, 'Id_usuarios'),
            ],
            'contrasenia'       => ['nullable','string','min:6'],
            'profesion'         => ['nullable','string','max:255'],
            'rol_componentes'   => ['nullable','string','max:255'], // NUEVO CAMPO
        ]);

        return DB::transaction(function () use ($validated, $profesor) {
            // Persona
            $profesor->persona->Nombre               = $validated['nombre'];
            $profesor->persona->Apellido             = $validated['apellido'];
            $profesor->persona->Genero               = $validated['genero'] ?? null;
            $profesor->persona->Direccion_domicilio  = $validated['direccion'] ?? null;
            $profesor->persona->Fecha_nacimiento     = $validated['fecha_nacimiento'] ?? null;
            $profesor->persona->Celular              = $validated['celular'] ?? null;
            $profesor->persona->Id_roles             = $validated['id_roles'];
            $profesor->persona->save();

            // Usuario
            $profesor->usuario->Correo = $validated['correo'];
            if (!empty($validated['contrasenia'])) {
                $profesor->usuario->Contrasenia = Hash::make($validated['contrasenia']);
            }
            $profesor->usuario->save();

            // Profesor
            $profesor->Profesion       = $validated['profesion'] ?? null;
            $profesor->Rol_componentes = $validated['rol_componentes'] ?? null;
            $profesor->save();

            return redirect()->back()->with('success', 'Profesor actualizado correctamente.');
        });
    }

    public function destroy(Request $request, $id)
    {
        $cascade = filter_var($request->input('cascade'), FILTER_VALIDATE_BOOL);

        try {
            DB::beginTransaction();

            $profesor = Profesor::with(['persona','usuario'])->findOrFail($id);

            if ($cascade) {
                $persona = $profesor->persona;
                $usuario = $profesor->usuario;

                $profesor->delete();
                if ($usuario) $usuario->delete();
                if ($persona) $persona->delete();
            } else {
                $profesor->delete();
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
