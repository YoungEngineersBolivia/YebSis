@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ auto_asset('css/profesor/homeProfesor.css') }}">
@endsection

@section('content')
    <div class="container py-4">
        <div class="row g-3 justify-content-center">
            {{-- Botón de Alumnos --}}
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profesor.alumnos') }}" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg py-4 shadow-sm">
                            <i class="bi bi-mortarboard display-6 mb-2 d-block"></i>
                            <span class="h5">Alumnos</span>
                        </button>
                    </div>
                </a>
            </div>

            @php
                $profesor = auth()->user()->persona->profesor ?? null;
            @endphp

            {{-- Botón de Inventario --}}
            @if($profesor && $profesor->Rol_componentes === 'Inventario')
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profesor.componentes.inventario') }}" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-success btn-lg py-4 shadow-sm">
                            <i class="bi bi-box-seam display-6 mb-2 d-block"></i>
                            <span class="h5">Inventario</span>
                        </button>
                    </div>
                </a>
            </div>
            @endif

            {{-- Botón de Componentes Asignados --}}
            @if($profesor && $profesor->Rol_componentes === 'Tecnico')
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profesor.componentes.motores-asignados') }}" class="text-decoration-none">
                    <div class="d-grid">
                        <button class="btn btn-warning btn-lg py-4 shadow-sm text-white">
                            <i class="bi bi-tools display-6 mb-2 d-block"></i>
                            <span class="h5">Componentes asignados</span>
                        </button>
                    </div>
                </a>
            </div>
            @endif

            {{-- Botón de Clases de Prueba --}}
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


<script src="{{ auto_asset('js/profesor/homeProfesor.js') }}"></script>
@endsection