<?php

namespace App\Http\Controllers;

use App\Models\Graduados;

class GraduadoController extends Controller
{
    public function index()
    {
        $graduados = Graduados::with([
            'estudiante.persona',
            'programa',
            'profesor.persona'
        ])->get();

        return view('administrador.graduadosAdministrador', compact('graduados'));
    }
}
