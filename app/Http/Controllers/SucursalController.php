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

}
