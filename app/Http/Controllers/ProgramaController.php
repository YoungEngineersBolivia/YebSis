<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa; 
use App\Models\Modelo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgramaController extends Controller
{
    public function index()
    {
        $programas = Programa::orderBy('Id_programas', 'desc')->paginate(10);
        return view('administrador.programasAdministrador', compact('programas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'rango_edad' => 'required|string|max:100',
            'duracion' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo' => 'required|string|in:programa,taller'
        ]);

        try {
            $programa = new Programa();
            $programa->Nombre = $request->nombre;
            $programa->Costo = $request->costo;
            $programa->Rango_edad = $request->rango_edad;
            $programa->Duracion = $request->duracion;
            $programa->Descripcion = $request->descripcion;
            $programa->Tipo = $request->tipo;

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . Str::random(10) . '.' . $imagen->getClientOriginalExtension();
                $rutaImagen = $imagen->storeAs('programas', $nombreImagen, 'public');
                $programa->Imagen = $rutaImagen;
            }

            $programa->save();

            return redirect()->route('programas.index')->with('success', 'Programa creado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el programa: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $programa = Programa::findOrFail($id);
            $modelos = Modelo::where('Id_programa', $id)
                            ->orderBy('created_at', 'desc')
                            ->get();
            
            return view('administrador.modelosPrograma', compact('programa', 'modelos'));
        } catch (\Exception $e) {
            return redirect()->route('programas.index')->with('error', 'Programa no encontrado');
        }
    }

  public function edit($id)
{
    $programa = Programa::findOrFail($id);
    return response()->json($programa);
}



    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'rango_edad' => 'required|string|max:100',
            'duracion' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'tipo' => 'required|string|in:programa,taller',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $programa = Programa::findOrFail($id);
            $programa->Nombre = $request->nombre;
            $programa->Costo = $request->costo;
            $programa->Rango_edad = $request->rango_edad;
            $programa->Duracion = $request->duracion;
            $programa->Descripcion = $request->descripcion;
            $programa->Tipo = $request->tipo;

            if ($request->hasFile('imagen')) {
                if ($programa->Imagen && Storage::disk('public')->exists($programa->Imagen)) {
                    Storage::disk('public')->delete($programa->Imagen);
                }
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . Str::random(10) . '.' . $imagen->getClientOriginalExtension();
                $rutaImagen = $imagen->storeAs('programas', $nombreImagen, 'public');
                $programa->Imagen = $rutaImagen;
            }

            $programa->save();

            return redirect()->route('programas.index')->with('success', 'Programa actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el programa: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
        {
            $programa = Programa::findOrFail($id);

            // Si tiene imagen, eliminarla
            if ($programa->Imagen) {
                Storage::delete($programa->Imagen);
            }

            $programa->delete();

            // Redirigir con mensaje de sesión
            return redirect()->route('programas.index')
                            ->with('success', 'Programa eliminado exitosamente');
        }

}