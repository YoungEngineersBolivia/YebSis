<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #0d6efd;
            font-size: 24pt;
            margin-bottom: 5px;
        }

        .header .subtitle {
            color: #6c757d;
            font-size: 10pt;
            margin-top: 5px;
        }

        .info-section {
            margin-bottom: 20px;
            font-size: 10pt;
            color: #495057;
        }

        .info-section strong {
            color: #212529;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background-color: #0d6efd;
            color: white;
        }

        thead th {
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11pt;
        }

        tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        tbody td {
            padding: 10px 8px;
            font-size: 10pt;
        }

        .codigo {
            font-weight: bold;
            color: #0d6efd;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }

        .total-count {
            background-color: #e7f3ff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 11pt;
        }

        .total-count strong {
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lista de Estudiantes</h1>
        <div class="subtitle">Young Engineers Bolivia - Sistema YebSis</div>
    </div>

    <div class="info-section">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <div class="total-count">
        <strong>Total de estudiantes:</strong> {{ $estudiantes->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Código</th>
                <th style="width: 30%;">Nombre</th>
                <th style="width: 30%;">Apellido</th>
                <th style="width: 25%;">Programa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($estudiantes as $estudiante)
                @php
                    $persona = $estudiante->persona ?? null;
                    $nombre = $persona->Nombre ?? 'Sin nombre';
                    $apellido = $persona->Apellido ?? 'Sin apellido';
                    $programa = $estudiante->programa->Nombre ?? 'Sin programa';
                @endphp
                <tr>
                    <td class="codigo">{{ $estudiante->Cod_estudiante }}</td>
                    <td>{{ $nombre }}</td>
                    <td>{{ $apellido }}</td>
                    <td>{{ $programa }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px; color: #6c757d;">
                        No hay estudiantes registrados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Documento generado automáticamente por el Sistema YebSis</p>
        <p>Young Engineers Bolivia © {{ date('Y') }}</p>
    </div>
</body>
</html>
