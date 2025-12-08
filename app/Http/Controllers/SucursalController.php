<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function index()
{
    $sucursales = Sucursal::all();
    return view('administrador.sucursalesAdministrador', compact('sucursales'));
}


    public function store(Request $request)
{
    $request->validate([
        'Nombre' => 'required',
        'Direccion' => 'required',
    ]);

    Sucursal::create($request->all());

   
    return redirect()->route('sucursales.index')->with('success', 'Sucursal agregada correctamente');
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required',
            'Direccion' => 'required',
        ]);

        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update($request->all());

        return redirect()->route('sucursales.index')->with('success', 'Sucursal actualizada correctamente');
    }

    public function destroy($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->delete();

        return redirect()->route('sucursales.index')->with('success', 'Sucursal eliminada correctamente');
    }

}
