<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Asistencia</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .badge { padding: 3px 6px; border-radius: 3px; color: white; display: inline-block; }
        .bg-success { background-color: #198754; color: white; }
        .bg-danger { background-color: #dc3545; color: white; }
        .bg-warning { background-color: #ffc107; color: black; }
        .bg-info { background-color: #0dcaf0; color: black; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte General de Asistencia</h2>
        <p>Generado el: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Estudiante</th>
                <th>Profesor</th>
                <th>Programa</th>
                <th>Estado</th>
                <th>Observación</th>
                <th>Reprogramado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asistencias as $asistencia)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($asistencia->Fecha)->format('d/m/Y') }}</td>
                    <td>
                        {{ $asistencia->estudiante->persona->Nombre }} {{ $asistencia->estudiante->persona->Apellido }}
                        <br><small>{{ $asistencia->estudiante->Cod_estudiante }}</small>
                    </td>
                    <td>{{ $asistencia->profesor->persona->Nombre }} {{ $asistencia->profesor->persona->Apellido }}</td>
                    <td>{{ $asistencia->programa->Nombre ?? '-' }}</td>
                    <td>
                        @if($asistencia->Estado == 'Asistio')
                            <span class="badge bg-success">Asistió</span>
                        @elseif($asistencia->Estado == 'Falta')
                            <span class="badge bg-danger">Falta</span>
                        @elseif($asistencia->Estado == 'Licencia')
                            <span class="badge bg-warning">Licencia</span>
                        @elseif($asistencia->Estado == 'Reprogramado')
                            <span class="badge bg-info">Reprogramado</span>
                        @endif
                    </td>
                    <td>{{ $asistencia->Observacion ?? '-' }}</td>
                    <td>
                        @if($asistencia->Fecha_reprogramada)
                            {{ \Carbon\Carbon::parse($asistencia->Fecha_reprogramada)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
