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
        // Se define la variable $notificaciones
        $notificaciones = Notificacion::orderBy('Fecha', 'desc')->limit(10)->get();

        return view('administrador.pubnotAdministrador', compact('publicaciones', 'notificaciones'));
    }

    // Guardar nueva publicación
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('publicaciones', 'public');
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

        // Aquí podrías agregar la lógica para crear notificaciones a tutores

        return redirect()->route('publicaciones.index')->with('success', 'Publicación creada correctamente.');
    }

    // Eliminar publicación
    public function destroy($id)
    {
        $publicacion = Publicacion::findOrFail($id);
        $publicacion->delete();

        return redirect()->route('publicaciones.index')->with('success', 'Publicación eliminada correctamente.');
    }
}