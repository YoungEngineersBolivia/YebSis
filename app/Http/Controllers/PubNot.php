<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicacion;
use App\Models\Notificacion;

class PubNot extends Controller
{
    public function index()
    {
        $publicaciones = Publicacion::orderBy('created_at', 'desc')->get();
        $notificaciones = Notificacion::orderBy('Fecha', 'desc')->limit(10)->get();
        $tutores = \App\Models\Tutores::with('persona')->orderBy('Id_tutores', 'desc')->get();

        return view('administrador.pubnotAdministrador', compact('publicaciones', 'notificaciones', 'tutores'));
    }

    // Guardar nueva publicación
    public function store(Request $request)
    {
        // Si viene tutores[] es notificación, si viene imagen es publicación
        if ($request->has('tutores')) {
            // Validar notificación
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'tutores' => 'required|array|min:1',
                'imagen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);
            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('notificaciones', 'public');
            }
            $tutores = $request->input('tutores');
            foreach ($tutores as $idTutor) {
              Notificacion::create([
                    'Nombre' => $request->nombre,
                    'Descripcion' => $request->descripcion,
                    'Imagen' => $imagenPath ? str_replace('public/', '', $imagenPath) : null,
                    'Fecha' => now()->toDateString(),
                    'Hora' => now()->toTimeString(),
                    'Estado' => true,
                    'Id_tutores' => $idTutor,
                ]);
            }
            return redirect()->route('publicaciones.index')->with('success', 'Notificación enviada correctamente.');
        } else {
            // Validar publicación
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'imagen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);
            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                    $file = $request->file('imagen'); // <-- asigna la variable aquí

                    $nombreOriginal = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $nombreArchivo = $nombreOriginal . '_' . time() . '.' . $extension;

                    $imagenPath = $file->storeAs('publicaciones', $nombreArchivo, 'public');
                } else {
                    $imagenPath = null;
                }
            
            Publicacion::create([
                'Nombre' => $request->nombre,
                'Descripcion' => $request->descripcion,
                'Imagen' => $imagenPath ? str_replace('public/', '', $imagenPath) : null,
                'Fecha' => now()->toDateString(),
                'Hora' => now()->toTimeString(),
                'Estado' => true,
                'Id_personas' => auth()->user()->Id_personas, // Ajusta según tu autenticación
            ]);
            return redirect()->route('publicaciones.index')->with('success', 'Publicación creada correctamente.');
        }
    }

    // Eliminar publicación
    public function destroy($id)
    {
        $publicacion = Publicacion::findOrFail($id);
        $publicacion->delete();

        return redirect()->route('publicaciones.index')->with('success', 'Publicación eliminada correctamente.');
    }

}