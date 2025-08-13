<?php

namespace App\Http\Controllers;
use App\Models\Egresos;
use Illuminate\Http\Request;

class EgresosController extends Controller
{
    public function index()
{
        $egresos = Egresos::all();
        return view('administrador.egresosAdministrador',compact('egresos'));
}

    public function store(Request $request)
    {
        $request -> validate([
            'Tipo'=> 'required',
            'Descripcion_egreso' =>'required',
            'Fecha_egreso' => 'required',
            'Monto_egreso' => 'requiered'
        ]);
        Egresos::create($request->all());

        return redirect()-> route('egresos.index')->with('success','Egreso registrado correctamente');
    }
}
