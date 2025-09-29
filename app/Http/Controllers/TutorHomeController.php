<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutores;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Auth;

class TutorhomeController extends Controller
{
    public function index()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();
        
        // Buscar el tutor relacionado con el usuario
        $tutor = Tutores::with([
            'persona',
            'estudiantes.persona',
            'estudiantes.programa',
            'estudiantes.sucursal',
            'estudiantes.profesor.persona'
        ])
        ->where('Id_usuarios', $usuario->Id_usuarios)
        ->first();

        // Si no se encuentra el tutor, redirigir con mensaje de error
        if (!$tutor) {
            return redirect()->back()->with('error', 'No se encontró información del tutor');
        }

        // Obtener todos los estudiantes del tutor
        $estudiantes = Estudiante::with([
            'persona',
            'programa',
            'sucursal',
            'profesor.persona'
        ])
        ->where('Id_tutores', $tutor->Id_tutores)
        ->get();

        return view('tutor/homeTutor', compact('tutor', 'estudiantes'));
        
    }
}