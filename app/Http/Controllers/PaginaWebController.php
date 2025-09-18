<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\Publicacion; // Importa el modelo

class PaginaWebController extends Controller
{
    public function home()
    {
        // Trae todos los programas
        $programas = Programa::orderBy('Id_programas', 'desc')->get();

        // Trae todas las publicaciones activas, ordenadas por fecha y hora
        $publicaciones = Publicacion::where('Estado', 1    )
                                    ->orderBy('Fecha', 'desc')
                                    ->orderBy('Hora', 'desc')
                                    ->get();

        // Pasa ambas variables a la vista
        return view('paginaWeb.home', compact('programas', 'publicaciones'));
    }
}

        
    