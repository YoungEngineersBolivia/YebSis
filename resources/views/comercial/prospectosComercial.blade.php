@extends('administrador.baseAdministrador')

@section('content')
    <h2>Lista de Prospectos</h2>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Celular</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prospectos as $prospecto)
                <tr>
                    <td>{{ $prospecto->Nombre }}</td>
                    <td>{{ $prospecto->Apellido }}</td>
                    <td>{{ $prospecto->Celular }}</td>
                    <td>{{ $prospecto->Estado_prospecto }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
