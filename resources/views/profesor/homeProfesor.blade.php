@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ auto_asset('css/profesor/homeProfesor.css') }}">
@endsection

@section('content')
    <main class="main-content">
        {{-- Botón de Alumnos (siempre visible) --}}
        <a href="{{ route('profesor.alumnos') }}">
            <button class="menu-button assigned">
                <i class="bi bi-mortarboard"></i> Alumnos
            </button>
        </a>

        @php
            $profesor = auth()->user()->persona->profesor ?? null;
        @endphp

        {{-- Botón de Inventario (solo si tiene rol Inventario) --}}
        @if($profesor && $profesor->Rol_componentes === 'Inventario')
            <a href="{{ route('profesor.componentes.inventario') }}">
                <button class="menu-button assigned">
                    <i class="bi bi-box-seam"></i> Inventario
                </button>
            </a>
        @endif

        {{-- Botón de Componentes Asignados (solo si tiene rol Tecnico) --}}
        @if($profesor && $profesor->Rol_componentes === 'Tecnico')
            <a href="{{ route('profesor.componentes.motores-asignados') }}">
                <button class="menu-button assigned">
                    <i class="bi bi-tools"></i> Componentes asignados
                </button>
            </a>
        @endif
    </main>

<script src="{{ auto_asset('js/profesor/homeProfesor.js') }}"></script>
@endsection