@extends('administrador.baseAdministrador')

@section('title', 'Dashboard')
@section('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ auto_asset('css/administrador/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="mt-2 text-start">

        {{-- Header con información temporal --}}
        {{-- Header con filtros --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h1><i class="fas fa-chart-line text-primary"></i> Dashboard</h1>
                <p class="text-muted mb-0">Visualizando datos de <strong>{{ $estadisticasTiempo['mes_nombre'] }}
                        {{ $estadisticasTiempo['anio_actual'] }}</strong></p>
            </div>
            <div class="col-md-6 text-end">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="d-inline-flex gap-2">
                    <select name="mes" class="form-select form-select-sm" onchange="this.form.submit()">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $estadisticasTiempo['mes_num'] == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($m)->monthName }}
                            </option>
                        @endfor
                    </select>
                    <input type="number" name="anio" class="form-control form-control-sm" min="2000"
                        value="{{ $estadisticasTiempo['anio_actual'] }}" onchange="this.form.submit()">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary"
                        title="Reiniciar filtros">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </form>
            </div>
        </div>


        {{-- Métricas principales mejoradas --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card metric-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted"><i class="fas fa-calendar-alt"></i> Ingresos Este Mes</h6>
                                <h3>Bs {{ number_format($ingresosMesActual, 2, '.', ',') }}</h3>
                                @if($crecimientoIngresos != 0)
                                    <small class="{{ $crecimientoIngresos > 0 ? 'growth-positive' : 'growth-negative' }}">
                                        <i class="fas fa-arrow-{{ $crecimientoIngresos > 0 ? 'up' : 'down' }}"></i>
                                        {{ abs(round($crecimientoIngresos, 1)) }}% vs mes anterior
                                    </small>
                                @endif
                            </div>
                            <div class="text-primary">
                                <i class="fas fa-calendar-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card metric-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted"><i class="fas fa-minus-circle"></i> Egresos Este Mes</h6>
                                <h3 class="text-danger">Bs {{ number_format($egresosMesActual, 2, '.', ',') }}</h3>
                                <small class="text-muted">Gatos registrados</small>
                            </div>
                            <div class="text-danger">
                                <i class="fas fa-receipt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card metric-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted"><i class="fas fa-hand-holding-usd"></i> Balance Mes</h6>
                                <h3 class="{{ $balanceMesActual >= 0 ? 'text-success' : 'text-danger' }}">
                                    Bs {{ number_format($balanceMesActual, 2, '.', ',') }}
                                </h3>
                                <small class="text-muted">Neto mensual</small>
                            </div>
                            <div class="{{ $balanceMesActual >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas fa-balance-scale fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Notificación de Clases de Prueba Pendientes --}}
        @if(isset($clasesPruebaPendientes) && $clasesPruebaPendientes->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-warning shadow-sm">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Clases de Prueba Pendientes</h5>
                            <span class="badge bg-dark">{{ $clasesPruebaPendientes->count() }}</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($clasesPruebaPendientes as $clase)
                                    @php
                                        $fechaHoraClase = \Carbon\Carbon::parse($clase->Fecha_clase . ' ' . $clase->Hora_clase);
                                        $esPasada = $fechaHoraClase->isPast();
                                    @endphp
                                    <div class="list-group-item {{ $esPasada ? 'bg-danger-subtle' : '' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <strong>{{ $clase->Nombre_Estudiante }}</strong>
                                                @if($esPasada && $clase->Asistencia === 'pendiente')
                                                    <i class="bi bi-exclamation-triangle-fill text-danger ms-1"
                                                        title="Clase atrasada"></i>
                                                @endif
                                                <div class="small text-muted">
                                                    <i
                                                        class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($clase->Fecha_clase)->format('d/m/Y') }}
                                                    <i
                                                        class="bi bi-clock ms-2 me-1"></i>{{ \Carbon\Carbon::parse($clase->Hora_clase)->format('H:i') }}
                                                </div>
                                                @if($clase->Asistencia !== 'pendiente')
                                                    <div class="mt-1">
                                                        <span
                                                            class="badge {{ $clase->Asistencia === 'asistio' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $clase->Asistencia === 'asistio' ? 'Asistió' : 'No asistió' }}
                                                        </span>
                                                        <small class="text-muted ms-1">
                                                            Marcado por:
                                                            @php
                                                                $marcadoPor = $clase->usuarioAsistencia?->persona?->nombre_completo
                                                                    ?? $clase->usuarioAsistencia?->Correo
                                                                    ?? 'Sistema';
                                                             @endphp
                                                            <strong>{{ $marcadoPor }}</strong>
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                @if($clase->Asistencia === 'pendiente')
                                                    <button onclick="confirmarAsistenciaAdmin({{ $clase->Id_clasePrueba }}, 'asistio')"
                                                        class="btn btn-outline-success" title="Marcar como Asistió">
                                                        <i class="bi bi-check-lg me-1"></i> Asistió
                                                    </button>
                                                    <button
                                                        onclick="confirmarAsistenciaAdmin({{ $clase->Id_clasePrueba }}, 'no_asistio')"
                                                        class="btn btn-outline-danger" title="Marcar como Falta">
                                                        <i class="bi bi-x-lg me-1"></i> No Asistió
                                                    </button>
                                                @else
                                                    <button onclick="descartarNotificacion({{ $clase->Id_clasePrueba }})"
                                                        class="btn btn-primary btn-sm px-3" title="Quitar del dashboard">
                                                        <i class="bi bi-check-circle me-1"></i> OK
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        @if($clase->Asistencia === 'pendiente')
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control"
                                                    id="comentario_admin_{{ $clase->Id_clasePrueba }}" value="{{ $clase->Comentarios }}"
                                                    placeholder="Añadir comentario (Recomendado)">
                                                <button class="btn btn-outline-secondary"
                                                    onclick="guardarComentarioAdmin({{ $clase->Id_clasePrueba }})"
                                                    title="Guardar comentario solo">
                                                    <i class="bi bi-save"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-light text-center">
                            <small class="text-muted">Mostrando todas las clases de prueba pendientes</small>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Gráficos de ingresos --}}
        <div class="row mb-4">
            <div class="col-lg-8 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-chart-area"></i> Ingresos Últimos 30 Días
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="ingresosDiarios"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-trophy"></i> Top 5 Mejores Días del Mes
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($topDiasIngresos as $index => $dia)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $dia['fecha'] }}</strong><br>
                                        <small class="text-muted">{{ $dia['dia_semana'] }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success rounded-pill">{{ $index + 1 }}</span>
                                        <strong>Bs {{ number_format($dia['total'], 2, '.', ',') }}</strong>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    No hay datos disponibles
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráfico mensual y información temporal --}}
        <div class="row mb-4">
            <div class="col-lg-8 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-chart-bar"></i> Comparativa Ingresos vs Egresos por Mes
                        ({{ $estadisticasTiempo['anio_actual'] }})
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="comparativaAnual"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <i class="fas fa-clock"></i> Información Temporal
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <h4 class="text-primary">{{ $estadisticasTiempo['dias_transcurridos_mes'] }}</h4>
                                <small>Días del mes</small>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-danger">{{ $estadisticasTiempo['dias_restantes_mes'] }}</h4>
                                <small>Días restantes</small>
                            </div>
                            <div class="col-12 mb-3">
                                <h4 class="text-info">Q{{ $estadisticasTiempo['trimestre_actual'] }}</h4>
                                <small>Trimestre actual</small>
                            </div>
                            <div class="col-12">
                                <div class="progress mb-2">
                                    @php
                                        $porcentajeMes = ($estadisticasTiempo['dias_transcurridos_mes'] / $estadisticasTiempo['dias_totales_mes']) * 100;
                                    @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $porcentajeMes }}%"></div>
                                </div>
                                <small>{{ round($porcentajeMes, 1) }}% del mes seleccionado completado</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alumnos por programa (manteniendo tu estructura original) --}}
        @foreach($sucursales as $sucursal)
            <div class="card mt-3 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-users"></i> Alumnos por programa en {{ $sucursal->Nombre }}
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Programa</th>
                                    <th>Total de Alumnos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alumnosPorSucursal[$sucursal->Id_sucursales] as $row)
                                    <tr>
                                        <td>{{ $row->programa }}</td>
                                        <td><strong>{{ $row->total }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
        @endforeach

        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            window.ingresosPorDia = @json($ingresosPorDia->pluck('total'));
            window.fechasPorDia = @json($ingresosPorDia->pluck('fecha'));
            window.graficoAnual = @json($graficoMensual);

            function guardarComentarioAdmin(id) {
                const comment = document.getElementById(`comentario_admin_${id}`).value;
                fetch(`/administrador/clases-prueba/${id}/comentarios`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ comentarios: comment })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            toast.fire({
                                icon: 'success',
                                title: 'Comentario guardado'
                            });
                        }
                    });
            }

            function confirmarAsistenciaAdmin(id, estado) {
                const commentInput = document.getElementById(`comentario_admin_${id}`);
                const comentario = commentInput.value.trim();
                const textoAccion = estado === 'asistio' ? 'marcar como ASISTIÓ' : 'marcar como FALTA';

                let confirmOptions = {
                    title: '¿Confirmar asistencia?',
                    text: `Vas a ${textoAccion}.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, enviar',
                    cancelButtonText: 'Cancelar'
                };

                if (comentario === '') {
                    confirmOptions.title = '¡Atención!';
                    confirmOptions.text = `Estás a punto de ${textoAccion} SIN COMENTARIOS. ¿Estás seguro? Es recomendable añadir una observación.`;
                    confirmOptions.icon = 'warning';
                    confirmOptions.confirmButtonColor = '#d33';
                }

                Swal.fire(confirmOptions).then((result) => {
                    if (result.isConfirmed) {
                        // Paso 1: Guardar comentario
                        fetch(`/administrador/clases-prueba/${id}/comentarios`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ comentarios: commentInput.value })
                        })
                            .then(() => {
                                // Paso 2: Guardar asistencia
                                return fetch(`/administrador/clases-prueba/${id}/asistencia`, {
                                    method: 'PUT',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: JSON.stringify({ asistencia: estado })
                                });
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: '¡Listo!',
                                        text: 'La asistencia ha sido registrada.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload(); // Recargar para mostrar quién lo marcó y el botón OK
                                    });
                                } else {
                                    Swal.fire('Error', data.message || 'Error desconocido al guardar.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                // Intentar leer el mensaje de error de la respuesta si es posible, sino genérico
                                Swal.fire('Error', 'Hubo un problema de conexión o del servidor.', 'error');
                            });
                    }
                });
            }

            function descartarNotificacion(id) {
                fetch(`/administrador/clases-prueba/${id}/dismiss`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Buscar el elemento y removerlo con una animación simple
                            const item = document.querySelector(`button[onclick="descartarNotificacion(${id})"]`).closest('.list-group-item');
                            if (item) {
                                item.style.transition = 'all 0.3s ease';
                                item.style.opacity = '0';
                                setTimeout(() => {
                                    item.remove();
                                    // Actualizar contador
                                    const badge = document.querySelector('.card-header .badge');
                                    if (badge) {
                                        let count = parseInt(badge.innerText);
                                        badge.innerText = Math.max(0, count - 1);
                                        if (count - 1 === 0) {
                                            location.reload(); // Recargar si es el último para limpiar el widget
                                        }
                                    }
                                }, 300);
                            }
                        }
                    });
            }
        </script>
        <script src="{{ auto_asset('js/administrador/dashboard.js') }}"></script>
@endsection