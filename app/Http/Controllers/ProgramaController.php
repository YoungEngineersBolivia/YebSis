<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa; 
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
        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'rango_edad' => 'required|string|max:100',
            'duracion' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        

        try {
            // Crear nuevo programa
            $programa = new Programa();
            $programa->Nombre = $request->nombre;
            $programa->Costo = $request->costo;
            $programa->Rango_edad = $request->rango_edad;
            $programa->Duracion = $request->duracion;
            $programa->Descripcion = $request->descripcion;

            // Manejar la subida de foto
           if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $contenidoFoto = file_get_contents($foto->getRealPath());
                $programa->Foto = $contenidoFoto;
            }


            $programa->save();

            return redirect()->back()->with('success', 'Programa creado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el programa: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $programa = Programa::findOrFail($id);
        return response()->json($programa);
    }

    public function edit($id)
{
    $programa = Programa::findOrFail($id);
    return view('programas.partials.form_edit', compact('programa'));
}

    public function update(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'rango_edad' => 'required|string|max:100',
            'duracion' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $programa = Programa::findOrFail($id);
            
            $programa->Nombre = $request->nombre;
            $programa->Costo = $request->costo;
            $programa->Rango_edad = $request->rango_edad;
            $programa->Duracion = $request->duracion;
            $programa->Descripcion = $request->descripcion;
            

            
            if ($request->hasFile('foto')) {
                
                if ($programa->Foto && Storage::disk('public')->exists($programa->Foto)) {
                    Storage::disk('public')->delete($programa->Foto);
                }

                $foto = $request->file('foto');
                $nombreFoto = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
                $rutaFoto = $foto->storeAs('programas', $nombreFoto, 'public');
                $programa->Foto = $rutaFoto;
            }

            $programa->save();

            return redirect()->back()->with('success', 'Programa actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el programa: ' . $e->getMessage());
        }
    }

        public function destroy($id)
    {
        try {
            $programa = Programa::findOrFail($id);

            
            if ($programa->Foto && Storage::disk('public')->exists($programa->Foto)) {
                Storage::disk('public')->delete($programa->Foto);
            }

            $programa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Programa eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el programa: ' . $e->getMessage()
            ], 500);
        }
    }
}