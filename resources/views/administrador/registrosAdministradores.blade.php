@extends('administrador.baseAdministrador')

@section('title', 'Registrar')

@section('content')
<div class="d-flex align-items-center gap-3">
    <h1 class="me-2">Registrar administrador</h1>
</div>

<form action="/administradores/registrar" method="POST">

    @csrf

    <div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <div class="input">
        <input type="text" name="nombre"class="form-control" aria-describedby="basic-addon3 basic-addon4" required>
    </div>
    </div>

    <div class="mb-3">
    <label for="nombre" class="form-label">Apellido</label>
    <div class="input">
        <input type="text" name="apellido"class="form-control" aria-describedby="basic-addon3 basic-addon4" required>
    </div>
    </div>


    <input type="email" name="correo" class="form-control mb-2" placeholder="Correo" required>

    <div class="d-flex gap-3 mb-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="genero" id="generoM" value="M" checked>
            <label class="form-check-label" for="generoM">M</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="genero" id="generoF" value="F">
            <label class="form-check-label" for="generoF">F</label>
        </div>
    </div>

    <input type="date" name="fecha_nacimiento" class="form-control mb-2" required>
    <input type="text" name="celular" class="form-control mb-2" placeholder="Teléfono" required>
    <input type="text" name="direccion_domicilio" class="form-control mb-3" placeholder="Dirección" required>

    <button type="submit" class="btn btn-primary">Registrar</button>
</form>


@endsection