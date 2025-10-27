@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profesor/homeProfesor.css') }}">
@endsection

@section('content')
    <main class="main-content">
      <a href="{{ route('profesor.alumnos') }}">
    <button class="menu-button assigned">Alumnos</button>
    </a>

    <a href="{{ route('profesor.inventario') }}">
        <button class="menu-button assigned" onclick="handleAssigned()">
            Inventario
        </button>
    </a>

    </main>

<script src="{{ auto_asset('js/profesor/homeProfesor.js') }}"></script>
@endsection

