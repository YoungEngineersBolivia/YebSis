@extends('administrador.baseAdministrador')

@section('title', 'Detalles del Estudiante')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    :root {
        --primary: #4f46e5;
        --secondary: #6366f1;
        --light: #f8fafc;
        --muted: #64748b;
        --radius: 16px;
    }

    body {
        background-color: var(--light);
    }

    .student-header {
        background: linear-gradient(120deg, var(--primary), var(--secondary));
        color: white;
        border-radius: var(--radius);
        padding: 2.5rem 1rem;
        text-align: center;
        margin-bottom: 2rem;
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.3);
    }

    .student-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: white;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        margin: 0 auto 1rem auto;
        border: 6px solid rgba(255, 255, 255, 0.3);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 6rem;
    }

    .info-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        padding: 1.5rem 1.8rem;
        transition: transform 0.2s ease, box-shadow 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.15);
    }

    .info-title {
        font-weight: 700;
        color: var(--primary);
        font-size: 1.2rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.4rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: var(--muted);
        font-weight: 500;
    }

    .info-value {
        color: #0f172a;
        font-weight: 600;
        text-align: right;
    }

    .status-badge {
        padding: 0.5rem 1.25rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #6b7280;
    }

    /* Footer fijo */
    .action-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: white;
        border-top: 2px solid #e5e7eb;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
        padding: 1rem 0;
        z-index: 100;
    }

    .action-footer .btn {
        border-radius: var(--radius);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .action-footer {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container mt-4 mb-5 pb-5">
    @php
        $persona = $estudiante->persona ?? null;
        $nombreCompleto = $persona ? trim(($persona->Nombre ?? '').' '.($persona->Apellido ?? '')) : 'Sin nombre';
        $iniciales = $persona ? strtoupper(substr($persona->Nombre ?? 'S', 0, 1) . substr($persona->Apellido ?? 'N', 0, 1)) : 'SN';

        $programa = $estudiante->programa ?? null;
        $sucursal = $estudiante->sucursal ?? null;
        $tutor = $estudiante->tutor ?? null;
        $tutorPersona = $tutor?->persona ?? null;

        $profesor = $estudiante->profesor ?? null;
        $profesorPersona = $profesor?->persona ?? null;
        $profesorNombre = $profesorPersona 
            ? trim(($profesorPersona->Nombre ?? '').' '.($profesorPersona->Apellido ?? ''))
            : 'Sin asignar';

        $estadoLower = Str::lower($estudiante->Estado ?? '');
        $esActivo = $estadoLower === 'activo';

        $edad = null;
        if($persona && $persona->Fecha_nacimiento) {
            $fechaNac = \Carbon\Carbon::parse($persona->Fecha_nacimiento);
            $edad = $fechaNac->age;
        }

        $horarios = \App\Models\Horario::where('Id_estudiantes', $estudiante->Id_estudiantes)
            ->with(['programa', 'profesor.persona'])
            ->get();
    @endphp

    {{-- Header principal --}}
    <div class="student-header">
        <div class="student-avatar">
            {{ $iniciales }}
        </div>
        <h2>{{ $nombreCompleto }}</h2>
        <p class="mb-1">Código: <strong>{{ $estudiante->Cod_estudiante }}</strong></p>
        <span class="status-badge {{ $esActivo ? 'status-active' : 'status-inactive' }}">
            {{ $esActivo ? 'Activo' : 'Inactivo' }}
        </span>
    </div>

    {{-- Tarjetas de información --}}
    <div class="info-grid">
        {{-- Datos personales --}}
        <div class="info-card">
            <div class="info-title"><i class="bi bi-person"></i> Datos personales</div>
            <div class="info-item"><span class="info-label">Nombre</span><span class="info-value">{{ $nombreCompleto }}</span></div>
            <div class="info-item"><span class="info-label">Género</span><span class="info-value">{{ $persona->Genero === 'M' ? 'Masculino' : 'Femenino' }}</span></div>
            <div class="info-item"><span class="info-label">Nacimiento</span><span class="info-value">{{ $persona->Fecha_nacimiento ? \Carbon\Carbon::parse($persona->Fecha_nacimiento)->format('d/m/Y') : '—' }}</span></div>
            <div class="info-item"><span class="info-label">Edad</span><span class="info-value">{{ $edad ?? '—' }}</span></div>
            <div class="info-item"><span class="info-label">Celular</span><span class="info-value">{{ $persona->Celular ?? 'No registrado' }}</span></div>
            <div class="info-item"><span class="info-label">Dirección</span><span class="info-value">{{ $persona->Direccion_domicilio ?? '—' }}</span></div>
        </div>

        {{-- Información académica --}}
        <div class="info-card">
            <div class="info-title"><i class="bi bi-mortarboard"></i> Información académica</div>
            <div class="info-item"><span class="info-label">Programa</span><span class="info-value">{{ $programa->Nombre ?? '—' }}</span></div>
            <div class="info-item"><span class="info-label">Tipo</span><span class="info-value">{{ $programa->Tipo ?? '—' }}</span></div>
            <div class="info-item"><span class="info-label">Profesor</span><span class="info-value">{{ $profesorNombre }}</span></div>
            <div class="info-item"><span class="info-label">Sucursal</span><span class="info-value">{{ $sucursal->Nombre ?? '—' }}</span></div>
            <div class="info-item"><span class="info-label">Cambio estado</span><span class="info-value">{{ \Carbon\Carbon::parse($estudiante->Fecha_estado)->format('d/m/Y') }}</span></div>
        </div>

        {{-- Tutor --}}
        <div class="info-card">
            <div class="info-title"><i class="bi bi-people"></i> Tutor</div>
            @if($tutor && $tutorPersona)
                <div class="info-item"><span class="info-label">Nombre</span><span class="info-value">{{ $tutorPersona->Nombre }} {{ $tutorPersona->Apellido }}</span></div>
                <div class="info-item"><span class="info-label">Celular</span><span class="info-value">{{ $tutorPersona->Celular ?? '—' }}</span></div>
                <div class="info-item"><span class="info-label">Parentesco</span><span class="info-value">{{ $tutor->Parentesco ?? '—' }}</span></div>
                <div class="info-item"><span class="info-label">NIT</span><span class="info-value">{{ $tutor->Nit ?? '—' }}</span></div>
                <div class="info-item"><span class="info-label">Descuento</span><span class="info-value">{{ $tutor->Descuento ? $tutor->Descuento.'%' : '—' }}</span></div>
            @else
                <p class="text-muted text-center mt-3 mb-0">No hay tutor asignado</p>
            @endif
        </div>

        {{-- Horarios --}}
        <div class="info-card">
            <div class="info-title"><i class="bi bi-calendar-week"></i> Horarios</div>
            @if($horarios->isEmpty())
                <p class="text-muted text-center mt-3 mb-0">No hay horarios asignados</p>
            @else
                @foreach($horarios as $horario)
                    @php
                        $prof = $horario->profesor?->persona;
                        $profNombre = $prof ? trim(($prof->Nombre ?? '').' '.($prof->Apellido ?? '')) : 'No asignado';
                    @endphp
                    @if($horario->Dia_clase_uno)
                        <div class="info-item">
                            <span class="info-label">{{ $horario->Dia_clase_uno }}</span>
                            <span class="info-value">{{ $horario->Horario_clase_uno }} <br><small class="text-muted">{{ $profNombre }}</small></span>
                        </div>
                    @endif
                    @if($horario->Dia_clase_dos)
                        <div class="info-item">
                            <span class="info-label">{{ $horario->Dia_clase_dos }}</span>
                            <span class="info-value">{{ $horario->Horario_clase_dos }} <br><small class="text-muted">{{ $profNombre }}</small></span>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- Footer fijo --}}
<div class="action-footer d-flex justify-content-center gap-3 flex-wrap">
    <a href="{{ route('estudiantes.planesPago', $estudiante->Id_estudiantes) }}" class="btn btn-primary">
        <i class="bi bi-cash"></i> Planes de Pago
    </a>
    <a href="{{ route('estudiantes.evaluaciones', $estudiante->Id_estudiantes) }}" class="btn btn-info text-white">
        <i class="bi bi-clipboard-check"></i> Evaluaciones
    </a>
    <a href="{{ route('estudiantes.horarios', $estudiante->Id_estudiantes) }}" class="btn btn-success text-white">
        <i class="bi bi-calendar-week"></i> Horarios
    </a>
    <a href="{{ route('estudiantes.editar', $estudiante->Id_estudiantes) }}" class="btn btn-warning text-dark">
        <i class="bi bi-pencil"></i> Editar
    </a>
    <form action="{{ route('estudiantes.cambiarEstado', $estudiante->Id_estudiantes) }}" method="POST" onsubmit="return confirm('¿Desea cambiar el estado del estudiante?')">
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-outline-secondary">
            <i class="bi bi-toggle-{{ $esActivo ? 'off' : 'on' }}"></i> {{ $esActivo ? 'Desactivar' : 'Activar' }}
        </button>
    </form>
</div>
@endsection