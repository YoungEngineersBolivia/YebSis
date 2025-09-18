<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;

class PaginaWebController extends Controller
{
    public function home()
    {
        $programas = Programa::orderBy('Id_programas', 'desc')->get(); // Trae todos los programas
        return view('paginaWeb.home', compact('programas')); // Pasa la variable a la vista
    }
}
