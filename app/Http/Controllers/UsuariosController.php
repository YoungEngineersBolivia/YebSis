<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UsuariosController extends Controller
{
    // Mostrar todos los usuarios
    public function index(Request $request)
    {
        $search = $request->input('search');  // Obtener el término de búsqueda

        // Realiza la consulta para obtener los usuarios filtrados
        $usuarios = Usuario::with(['persona.rol'])
            ->when($search, function($query, $search) {
                return $query->whereHas('persona', function($q) use ($search) {
                    $q->where('Nombre', 'like', "%$search%")
                      ->orWhere('Apellido', 'like', "%$search%")
                      ->orWhere('Correo', 'like', "%$search%");
                });
            })
            ->orderBy('Id_usuarios', 'desc')
            ->paginate(10);  // Pagina los resultados para no cargar demasiados registros

        return view('administrador.usuariosAdministrador', compact('usuarios'));
    }

    // Mostrar detalles de un usuario (para función "ver")
    public function show($id)
    {
        $usuario = Usuario::with('persona.rol')->findOrFail($id);

        return response()->json([
            'Nombre' => $usuario->persona->Nombre ?? '',
            'Apellido' => $usuario->persona->Apellido ?? '',
            'Correo' => $usuario->Correo,
            'Rol' => $usuario->persona->rol->Nombre_rol ?? '',
        ]);
    }

    // Obtener datos de usuario para edición
    public function edit($id)
    {
        $usuario = Usuario::with('persona.rol')->findOrFail($id);

        return response()->json([
            'Nombre' => $usuario->persona->Nombre ?? '',
            'Apellido' => $usuario->persona->Apellido ?? '',
            'Correo' => $usuario->Correo,
            'Rol' => $usuario->persona->rol->Nombre_rol ?? '',
        ]);
    }

    // Actualizar datos de usuario
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo'   => 'required|email|max:255|unique:usuarios,Correo,' . $id . ',Id_usuarios',
        ]);

        $usuario = Usuario::findOrFail($id);
        $persona = $usuario->persona;

        // Actualizar datos personales
        $persona->Nombre = $request->nombre;
        $persona->Apellido = $request->apellido;
        $persona->save();

        // Actualizar correo
        $usuario->Correo = $request->correo;
        $usuario->save();

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $usuario = Usuario::findOrFail($id);

            // Opcional: eliminar persona si no está relacionada con otra entidad
            $persona = $usuario->persona;

            $usuario->delete();

            if ($persona) {
                $persona->delete();
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
   
}
