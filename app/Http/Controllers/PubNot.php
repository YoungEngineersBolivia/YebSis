<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicacion;
use App\Models\Notificacion;

class PubNot extends Controller
{
    public function index()
    {
        try {
            $publicaciones = Publicacion::orderBy('created_at', 'desc')->get();
            $notificaciones = Notificacion::orderBy('Fecha', 'desc')->limit(10)->get();
            $tutores = \App\Models\Tutores::with(['persona', 'estudiantes'])->orderBy('Id_tutores', 'desc')->get();

            return view('administrador.pubnotAdministrador', compact('publicaciones', 'notificaciones', 'tutores'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar la página: ' . $e->getMessage());
        }
    }

    // Guardar nueva publicación
    public function store(Request $request)
    {
        // Validar publicación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        try {
            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $nombreArchivo = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $imagenPath = $file->storeAs('publicaciones', $nombreArchivo, 'public');
            }

            // Asegurar que tenemos un ID de persona del usuario autenticado
            $idPersona = auth()->user()->Id_personas ?? null;
            if (!$idPersona) {
                return back()->with('error', 'El usuario actual no tiene una persona asociada para realizar publicaciones.');
            }

            Publicacion::create([
                'Nombre' => $request->nombre,
                'Descripcion' => $request->descripcion,
                'Imagen' => $imagenPath,
                'Fecha' => now()->toDateString(),
                'Hora' => now()->toTimeString(),
                'Estado' => 'Activa', // Cambio de true (bool) a 'Activa' (string) según migración
                'Id_personas' => $idPersona,
            ]);

            return redirect()->route('publicaciones.index')->with('success', 'Publicación creada correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al crear la publicación: ' . $e->getMessage());
        }
    }

    // Guardar nueva notificación (para tutores)
    public function storeNotificacion(Request $request)
    {
        // Validar notificación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tutores' => 'required|array|min:1',
            'imagen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        try {
            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('notificaciones', 'public');
            }

            $tutoresSeleccionados = $request->input('tutores');
            foreach ($tutoresSeleccionados as $idTutor) {
                Notificacion::create([
                    'Nombre' => $request->nombre,
                    'Descripcion' => $request->descripcion,
                    'Imagen' => $imagenPath,
                    'Fecha' => now()->toDateString(),
                    'Hora' => now()->toTimeString(),
                    'Estado' => true,
                    'Id_tutores' => $idTutor,
                ]);
            }

            return redirect()->route('publicaciones.index')->with('success', 'Notificación enviada correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al enviar la notificación: ' . $e->getMessage());
        }
    }

    // Eliminar publicación
    public function destroy($id)
    {
        try {
            $publicacion = Publicacion::findOrFail($id);

            // Eliminar la imagen física del servidor si existe
            if ($publicacion->Imagen) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($publicacion->Imagen)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($publicacion->Imagen);
                }
            }

            $publicacion->delete();

            return redirect()->route('publicaciones.index')->with('success', 'Publicación eliminada y espacio liberado en el servidor.');
        } catch (\Exception $e) {
            return redirect()->route('publicaciones.index')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

}