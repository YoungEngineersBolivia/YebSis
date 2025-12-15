{{-- resources/views/profesor/menuAlumnosProfesor.blade.php --}}
@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ auto_asset('css/profesor/menuAlumnosProfesor.css') }}">
@endsection

@section('content')
    <div class="container py-4">
        <h2 class="text-center mb-4 fw-bold text-secondary">Menú de Alumnos</h2>
        <div class="row g-3 justify-content-center">
            
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profesor.asistencia.index') }}" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg py-4 shadow-sm">
                            <i class="bi bi-calendar-check display-6 mb-2 d-block"></i>
                            <span class="h5">Asistencia</span>
                        </button>
                    </div>
                </a>
            </div>
            
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profesor.listado-alumnos', ['tipo' => 'asignados']) }}" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-success btn-lg py-4 shadow-sm">
                            <i class="bi bi-people-fill display-6 mb-2 d-block"></i>
                            <span class="h5">Alumnos Asignados</span>
                        </button>
                    </div>
                </a>
            </div>
            
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profesor.listado-alumnos', ['tipo' => 'recuperatoria']) }}" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-warning btn-lg py-4 shadow-sm text-white">
                            <i class="bi bi-arrow-repeat display-6 mb-2 d-block"></i>
                            <span class="h5">Clase Recuperatoria</span>
                        </button>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profesor.clases-prueba.index') }}" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-info btn-lg py-4 shadow-sm text-white">
                            <i class="bi bi-chalkboard-teacher display-6 mb-2 d-block"></i>
                            <span class="h5">Clases de Prueba</span>
                        </button>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection