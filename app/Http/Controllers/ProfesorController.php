<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ProfesorController extends Controller
{
    public function homeProfesor()
    {
        $usuario = Auth::user(); // obtiene el usuario autenticado
        return view('profesor.homeProfesor', compact('usuario'));
    }
}
