@extends('administrador.baseAdministrador')

@section('title', 'Estudiantes')

@section('content')
    <h1>Lista de Estudiantes</h1>

    @if ($estudiantes->isEmpty())
        <p>No hay estudiantes registrados.</p>
    @else
        <ul>
            @foreach ($estudiantes as $estudiante)
                <li>
                    {{ $estudiante->persona->Nombre ?? 'Sin nombre' }} 
                    {{ $estudiante->persona->Apellido ?? '' }} - 
                    Estado: {{ $estudiante->Estado ?? 'Sin estado' }}

                    <a href="{{ url('/estudiante/' . $estudiante->Id_estudiantes) }}">Ver detalles</a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
