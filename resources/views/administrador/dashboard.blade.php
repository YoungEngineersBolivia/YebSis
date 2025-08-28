@extends('administrador.baseAdministrador')

@section('title', 'Dashboard')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-chart-line"></i> Dashboard Administrativo</h1>
        <p class="text-muted mb-0">Panel de control y métricas del sistema educativo</p>
    </div>

    {{-- Alumnos por programa --}}
   @foreach($sucursales as $sucursal)
    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-users"></i> Alumnos por programa en {{ $sucursal->Nombre }}
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Programa</th>
                        <th>Total de Alumnos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alumnosPorSucursal[$sucursal->Id_Sucursales] as $row)
                        <tr>
                            <td>{{ $row->programa }}</td>
                            <td><strong>{{ $row->total }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No hay datos disponibles</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endforeach


    {{-- Métricas --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6><i class="fas fa-arrow-up text-success"></i> Ingresos Totales</h6>
                    <h3>Bs {{ isset($ingresosTotales) ? number_format($ingresosTotales, 2, '.', ',') : '0.00' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6><i class="fas fa-arrow-down text-danger"></i> Egresos Totales</h6>
                    <h3>Bs {{ isset($egresosTotales) ? number_format($egresosTotales, 2, '.', ',') : '0.00' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6><i class="fas fa-building"></i> Sucursales</h6>
                    @isset($sucursales)
                        @forelse($sucursales as $sucursal)
                            <p class="mb-1">{{ $sucursal->Nombre }}: <strong>{{ $sucursal->total }}</strong></p>
                        @empty
                            <p class="text-muted">Sin sucursales disponibles</p>
                        @endforelse
                    @else
                        <p class="text-muted">Sin sucursales disponibles</p>
                    @endisset
                </div>
            </div>
        </div>
    </div>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
