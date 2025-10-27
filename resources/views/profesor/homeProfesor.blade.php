@extends('profesor.baseProfesor')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profesor/homeProfesor.css') }}">
@endsection

@section('content')
    <main class="main-content">
       <a href="{{ route('profesor.alumnos') }}">
            <button class="menu-button evaluate">Alumnos</button>
        </a>


        <a href="{{ route()}}"></a>
        <button class="menu-button assigned" onclick="handleAssigned()">
            Inventario
        </button>
    </main>

<script src="{{ auto_asset('js/profesor/homeProfesor.js') }}"></script>
@endsection
