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
                    // Buscar por nombre o apellido individual
                    $q->where('Nombre', 'like', "%$search%")
                      ->orWhere('Apellido', 'like', "%$search%")
                      // Buscar por nombre completo (nombre + apellido)
                      ->orWhereRaw("CONCAT(Nombre, ' ', Apellido) LIKE ?", ["%$search%"])
                      // Buscar por nombre completo invertido (apellido + nombre)
                      ->orWhereRaw("CONCAT(Apellido, ' ', Nombre) LIKE ?", ["%$search%"]);
                })
                // También buscar por correo
                ->orWhere('Correo', 'like', "%$search%");
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

    public function edit($id)
    {
        $usuario = Usuario::with('persona.rol')->find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json([
            'Nombre'   => $usuario->persona->Nombre ?? '',
            'Apellido' => $usuario->persona->Apellido ?? '',
            'Correo'   => $usuario->Correo,
            'Rol'      => $usuario->persona->rol->Nombre_rol ?? '',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:usuarios,Correo,' . $id . ',Id_usuarios',
        ]);

        $usuario = Usuario::findOrFail($id);
        $persona = $usuario->persona;

        $persona->Nombre = $request->nombre;
        $persona->Apellido = $request->apellido;
        $persona->save();

        $usuario->Correo = $request->correo;
        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado']);
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}