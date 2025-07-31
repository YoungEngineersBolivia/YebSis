<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutores; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TutoresController extends Controller
{
    public function index()
    {
        $tutores = tutor::orderBy('Id_tutores', 'desc')->paginate(10);
         return view('administrador.tutoresAdministrador', compact('tutores'));
    }

}