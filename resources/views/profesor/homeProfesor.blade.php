@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ auto_asset('css/profesor/homeProfesor.css') }}">
@endsection

@section('content')
    <main class="main-content">
      <a href="{{ route('profesor.alumnos') }}">
    <button class="menu-button assigned">Alumnos</button>
    </a>

    <a href="{{route('profesor.inventario.index')}}">
        <button class="menu-button assigned" onclick="handleAssigned()">
            Inventario
        </button>
    </a>

    <a href="{{route('profesor.inventario.mis-motores')}}">
        <button class="menu-button assigned" onclick="handleAssigned()">
            Componentes asignados
        </button>
    </a>

    </main>

<script src="{{ auto_asset('js/profesor/homeProfesor.js') }}"></script>
@endsection

