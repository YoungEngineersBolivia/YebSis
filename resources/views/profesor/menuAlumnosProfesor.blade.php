{{-- resources/views/profesor/menuAlumnosProfesor.blade.php --}}
@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ auto_asset('css/profesor/menuAlumnosProfesor.css') }}">
@endsection

@section('content')
    <main class="main-content">
        <a href="{{ route('profesor.asistencia.index') }}" class="menu-link">
            <button class="menu-button evaluate">Asistencia</button>
        </a>
        
        <a href="{{ route('profesor.listado-alumnos', ['tipo' => 'asignados']) }}" class="menu-link">
            <button class="menu-button assigned">Alumnos Asignados</button>
        </a>
        
        <a href="{{ route('profesor.listado-alumnos', ['tipo' => 'recuperatoria']) }}" class="menu-link">
            <button class="menu-button recovery">Alumno Registrado<br>Clase Recuperatoria</button>
        </a>

        <a href="{{ route('profesor.clases-prueba.index') }}" class="menu-link">
            <button class="menu-button assigned">Clases de Prueba</button>
        </a>
    </main>
@endsection