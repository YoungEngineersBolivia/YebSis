@extends('administrador.baseAdministrador')

@section('title', 'Registrar')

@section('content')
<div class="d-flex align-items-center gap-3">
    <h1 class="me-2">Registrar</h1>
    <div class="dropdown">
        <a class="text-muted text-decoration-none dropdown-toggle" href="#" role="button" id="dropdownRol"
           data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: 500;">
            seleccionar rol
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownRol">
            <li><a class="dropdown-item" href="#">Tutor</a></li>
            <li><a class="dropdown-item" href="#">Estudiante</a></li>
            <li><a class="dropdown-item" href="#">Profesor</a></li>
            <li><a class="dropdown-item" href="#">Administrador</a></li>
            <li><a class="dropdown-item" href="#">Comercial</a></li>
        </ul>
    </div>
</div>

<div class="d-flex mb-3">
  <label for="basic-url" class="form-label">Nombre</label>
  <div class="input">
    <input type="text" class="form-control">
  </div>
  <label for="basic-url" class="form-label">Apellido</label>
  <div class="input">
    <input type="text" class="form-control">
  </div>
</div>
@endsection