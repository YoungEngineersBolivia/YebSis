@extends('profesor.baseProfesor')

@section('title', 'Historial de Asistencia')

@section('content')
    <div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
        <!-- Header Section -->
        <div class="row mb-5 align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <a href="{{ route('profesor.asistencia.index') }}" class="btn btn-white btn-sm shadow-sm rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Nueva Asistencia
                    </a>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('profesor.home') }}" class="text-decoration-none text-muted">Inicio</a></li>
                            <li class="breadcrumb-item active fw-bold" aria-current="page text-primary">Historial</li>
                        </ol>
                    </nav>
                </div>
                <h1 class="display-6 fw-bold text-dark mb-1">
                    <i class="bi bi-clock-history text-primary me-2"></i>Historial de Asistencia
                </h1>
                <p class="text-secondary opacity-75">Gestiona y revisa todas las asistencias registradas cronológicamente.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="bg-white p-3 rounded-4 shadow-sm d-inline-block border">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                            <i class="bi bi-calendar-event fs-4"></i>
                        </div>
                        <div class="text-start">
                            <div class="text-muted small">Total Registros</div>
                            <div class="fs-4 fw-bold text-dark">{{ $historial->total() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-filter-left me-2"></i>Registros Guardados</h5>
                <span class="text-muted small">Ordenado por fecha actual</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0 custom-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Fecha y Día</th>
                                <th class="py-3 text-center">Resumen de Alumnos</th>
                                <th class="py-3">Asistencia Detallada</th>
                                <th class="py-3 text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historial as $registro)
                                @php
                                    $carbonDate = \Carbon\Carbon::parse($registro->Fecha);
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="date-badge text-center shadow-sm">
                                                <div class="month text-uppercase">{{ $carbonDate->isoFormat('MMM') }}</div>
                                                <div class="day fw-bold fs-4">{{ $carbonDate->format('d') }}</div>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $carbonDate->format('Y') }}</div>
                                                <div class="text-muted small text-capitalize">{{ $carbonDate->isoFormat('dddd') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="total-students-badge mx-auto shadow-sm">
                                            <span class="fs-5 fw-bold text-primary">{{ $registro->total }}</span>
                                            <small class="d-block text-muted" style="font-size: 0.65rem;">ALUMNOS</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress-container">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="text-success fw-bold"><i class="bi bi-check-circle me-1"></i>{{ $registro->asistieron }}</span>
                                                <span class="text-danger fw-bold">{{ $registro->faltaron }}<i class="bi bi-x-circle ms-1"></i></span>
                                            </div>
                                            <div class="progress rounded-pill shadow-none" style="height: 8px; background-color: #eee;">
                                                @php
                                                    $percent = $registro->total > 0 ? ($registro->asistieron / $registro->total) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $percent }}%"></div>
                                                <div class="progress-bar bg-danger rounded-pill" role="progressbar" style="width: {{ 100 - $percent }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            <a href="{{ route('profesor.asistencia.index') }}?fecha={{ $registro->Fecha }}"
                                               class="btn btn-primary btn-sm px-4 py-2 border-0">
                                                <i class="bi bi-pencil-square me-1"></i> Editar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-journal-x display-1 text-muted opacity-25 mb-3 d-block"></i>
                                            <h4 class="text-muted fw-bold">Sin Historial</h4>
                                            <p class="text-muted">Todavía no has registrado ninguna asistencia en el sistema.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="d-md-none p-3">
                    @foreach($historial as $registro)
                        @php $carbonDate = \Carbon\Carbon::parse($registro->Fecha); @endphp
                        <div class="card mb-3 border shadow-sm rounded-4 overflow-hidden">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="date-badge-sm text-center bg-primary text-white p-2 rounded-3" style="width: 50px;">
                                            <div class="small fw-bold">{{ $carbonDate->format('d') }}</div>
                                            <div style="font-size: 0.65rem;" class="text-uppercase">{{ $carbonDate->isoFormat('MMM') }}</div>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $carbonDate->isoFormat('dddd') }}</div>
                                            <small class="text-muted">{{ $carbonDate->format('Y') }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-primary border rounded-pill">{{ $registro->total }} Alumnos</span>
                                </div>
                                <div class="d-flex gap-2 mb-3">
                                    <div class="flex-grow-1 bg-success bg-opacity-10 p-2 rounded-3 text-center">
                                        <div class="small text-success fw-bold">Asistieron</div>
                                        <div class="fs-5 fw-bold text-success">{{ $registro->asistieron }}</div>
                                    </div>
                                    <div class="flex-grow-1 bg-danger bg-opacity-10 p-2 rounded-3 text-center">
                                        <div class="small text-danger fw-bold">Faltaron</div>
                                        <div class="fs-5 fw-bold text-danger">{{ $registro->faltaron }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('profesor.asistencia.index') }}?fecha={{ $registro->Fecha }}"
                                   class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                                    <i class="bi bi-pencil-square me-2"></i>Ver y Editar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="d-flex justify-content-center pb-5">
            <div class="pagination-wrapper shadow-lg rounded-pill p-2 bg-white">
                {{ $historial->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        :root {
            --primary-color: #0d6efd;
            --success-color: #198754;
            --danger-color: #dc3545;
        }

        /* Tables Styles */
        .custom-table thead th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            color: #495057;
            border-bottom: 2px solid #f8f9fa;
        }

        .custom-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f5;
        }

        .custom-table tbody tr:hover {
            background-color: #f8faff !important;
            transform: scale(1.002);
        }

        /* Decorative Components */
        .date-badge {
            width: 60px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            overflow: hidden;
            border-top: 4px solid var(--primary-color);
        }

        .date-badge .month {
            background: #f8f9fa;
            font-size: 0.65rem;
            color: #6c757d;
            padding: 2px 0;
            border-bottom: 1px solid #f1f3f5;
        }

        .date-badge .day {
            color: #212529;
            line-height: 1.2;
            padding: 5px 0;
        }

        .total-students-badge {
            width: 70px;
            height: 70px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border: 2px solid rgba(13, 110, 253, 0.1);
        }

        .progress-container {
            max-width: 200px;
        }

        /* Pagination Override */
        .pagination-wrapper .pagination {
            margin-bottom: 0;
        }
        
        .pagination-wrapper .page-link {
            border: none;
            padding: 8px 16px;
            margin: 0 4px;
            border-radius: 50% !important;
            font-weight: 600;
            color: #495057;
        }

        .pagination-wrapper .page-item.active .page-link {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
        }

        .btn-white {
            background: white;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        .btn-white:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card, .date-badge, .total-students-badge {
            animation: fadeIn 0.5s ease-out;
        }

        @media (max-width: 768px) {
            h1 { font-size: 1.5rem !important; }
            .breadcrumb { font-size: 0.75rem; }
        }
    </style>
@endsection