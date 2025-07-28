<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;

class ProgramaController extends Controller
{
    public function index()
    {
        $programas = Programa::paginate(10);
        return view('administrador.programasAdministrador', compact('programas'));
    }
}
