@extends('administrador.baseAdministrador')

@section('title', 'Dashboard')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<style>
  /* Estética tipo “tarjeta suave” */
  .card-soft { border:1px solid #e9ecef; border-radius:1rem; box-shadow:0 2px 10px rgba(0,0,0,.04); }
  .card-soft .card-header { background:#fff; border-bottom:1px solid #eef1f5; border-radius:1rem 1rem 0 0; font-weight:600; }
  .table-clean { margin:0; }
  .table-clean tr td, .table-clean tr th { border-top:1px solid #f2f4f7; }
  .table-clean tr:first-child td, .table-clean tr:first-child th { border-top:none; }
  .kpi { border-radius:1rem; padding:1.25rem; border:1px solid #e9ecef; background:#fff; box-shadow:0 2px 10px rgba(0,0,0,.04);}
  .kpi h2 { font-size:2.25rem; margin:0; line-height:1; font-weight:800; }
  .kpi .label { font-size:.95rem; color:#6c757d; margin-bottom:.35rem; display:flex; align-items:center; gap:.5rem; }
  .btn-soft-success{background:#e9f7ef;border:1px solid #c6efda;color:#198754;}
  .btn-soft-danger{background:#fdecec;border:1px solid #f7cfcf;color:#dc3545;}
  .stat-block{ border-radius:1rem; background:#fff; border:1px solid #e9ecef; box-shadow:0 2px 10px rgba(0,0,0,.04); padding:1.25rem 1.5rem;}
  .stat-title{ font-size:1.25rem; font-weight:800; margin-bottom:.5rem;}
  .stat-row{ display:flex; justify-content:space-between; align-items:center; font-size:1.5rem; font-weight:800;}
  .muted{ color:#6c757d; }
  .page-title{ font-weight:800; letter-spacing:.2px; }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4">

  {{-- Encabezado --}}
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
    <h1 class="page-title mb-2 mb-md-0"><i class="fas fa-chart-line me-2"></i>Dashboard Administrativo</h1>
    <p class="text-muted mb-0">Panel de control y métricas del sistema educativo</p>
  </div>

  {{-- Listas por programa por sucursal (estilo tarjetas lado a lado) --}}
  <div class="row g-3">
    @foreach($sucursales as $sucursal)
      <div class="col-12 col-md-6">
        <div class="card card-soft">
          <div class="card-header d-flex align-items-center justify-content-between">
            <span><i class="fa-solid fa-location-dot me-2 text-secondary"></i>Alumnos por programa en {{ $sucursal->Nombre }}</span>
            {{-- opcional: ícono de refrescar --}}
            <i class="fa-solid fa-rotate-right text-muted"></i>
          </div>
          <div class="card-body p-0">
            <table class="table table-clean mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">Programa</th>
                  <th class="text-end pe-3" style="width:150px">Alumnos por Programa</th>
                </tr>
              </thead>
              <tbody>
                @forelse($alumnosPorSucursal[$sucursal->Id_Sucursales] ?? [] as $row)
                  <tr>
                    <td class="ps-3">{{ $row->programa }}</td>
                    <td class="text-end pe-3">{{ $row->total }}</td>
                  </tr>
                @empty
                  <tr>
                    <td class="ps-3" colspan="2" class="text-muted">No hay datos</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  @php
    // Totales por sucursal para la tarjeta “Sucursales”
    $sumPorSucursal = [];
    foreach($sucursales as $s){
      $suma = 0;
      if(isset($alumnosPorSucursal[$s->Id_Sucursales])){
        foreach($alumnosPorSucursal[$s->Id_Sucursales] as $r){ $suma += (int) $r->total; }
      }
      $sumPorSucursal[$s->Nombre] = $suma;
    }
    $ingresos = isset($ingresosTotales) ? number_format($ingresosTotales, 0, '.', ',') : '0';
    $egresos  = isset($egresosTotales)  ? number_format($egresosTotales, 0, '.', ',')  : '0';
    $alAct = $alumnosActivos  ?? null;   // pásalo desde el controlador si lo tienes
    $alIna = $alumnosInactivos ?? null;  // pásalo desde el controlador si lo tienes
  @endphp

  {{-- Franja inferior: KPIs y tarjetas de totales (como la 2da imagen) --}}
  <div class="row g-3 mt-1 align-items-stretch">
    {{-- KPI Ingresos/Egresos con botones --}}
    <div class="col-12 col-lg-4">
      <div class="kpi mb-3">
        <div class="label"><i class="fa-solid fa-arrow-trend-up"></i> Ingresos totales</div>
        <div class="d-flex align-items-center justify-content-between">
          <h2>Bs {{ $ingresos }}</h2>
          <i class="fa-solid fa-rotate text-muted"></i>
        </div>
        <div class="mt-3">
          <a href="{{ route('pagos.create') }}" class="btn btn-soft-success btn-sm px-3">
            <i class="fa-solid fa-plus me-1"></i> Registrar pago
          </a>
        </div>
      </div>
      <div class="kpi">
        <div class="label"><i class="fa-solid fa-arrow-trend-down"></i> Egresos totales</div>
        <div class="d-flex align-items-center justify-content-between">
          <h2>Bs {{ $egresos }}</h2>
          <i class="fa-solid fa-rotate text-muted"></i>
        </div>
        <div class="mt-3">
          <a href="{{ route('egresos.create') }}" class="btn btn-soft-danger btn-sm px-3">
            <i class="fa-solid fa-plus me-1"></i> Registrar egreso
          </a>
        </div>
      </div>
    </div>

    {{-- Tarjeta: Alumnos (Activos / Inactivos) --}}
    <div class="col-12 col-lg-4">
      <div class="stat-block h-100">
        <div class="stat-title text-center">Alumnos</div>
        <div class="stat-row">
          <div class="muted">Activos</div>
          <div>{{ $alAct !== null ? $alAct : (array_sum(array_values($sumPorSucursal)) ?: 0) }}</div>
        </div>
        <hr class="my-2">
        <div class="stat-row">
          <div class="muted">Inactivos</div>
          <div>{{ $alIna !== null ? $alIna : 0 }}</div>
        </div>
      </div>
    </div>

    {{-- Tarjeta: Sucursales con totales a la derecha --}}
    <div class="col-12 col-lg-4">
      <div class="stat-block h-100">
        <div class="stat-title text-center">Sucursales</div>
        @forelse($sumPorSucursal as $nombre => $total)
          <div class="stat-row">
            <div class="muted">{{ $nombre }}</div>
            <div>{{ $total }}</div>
          </div>
          @if(!$loop->last)<hr class="my-2">@endif
        @empty
          <p class="text-muted mb-0">Sin sucursales</p>
        @endforelse
      </div>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
