<div class="card shadow-sm border-0 border-top border-primary border-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3 py-3">Nombre</th>
                        <th class="py-3">Apellido</th>
                        <th class="py-3">Programa</th>
                        <th class="py-3">Día</th>
                        <th class="py-3">Hora</th>
                        <th class="py-3">Profesor Asignado</th>
                        <th class="pe-3 py-3 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($horarios as $horario)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $horario->estudiante?->persona?->Nombre ?? '—' }}</td>
                            <td>{{ $horario->estudiante?->persona?->Apellido ?? '—' }}</td>
                            <td>{{ $horario->programa?->Nombre ?? '—' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $horario->Dia ?? '—' }}</span></td>
                            <td>{{ $horario->Hora ?? '—' }}</td>
                            <td>
                                @php $pp = $horario->profesor?->persona; @endphp
                                {{ ($pp?->Nombre && $pp?->Apellido) ? ($pp->Nombre . ' ' . $pp->Apellido) : 'Sin profesor' }}
                            </td>
                            <td class="pe-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary shadow-sm"
                                        title="Editar horario" data-bs-toggle="modal" data-bs-target="#modalEditar"
                                        data-id="{{ $horario->Id_horarios }}"
                                        data-estudiante="{{ $horario->Id_estudiantes }}"
                                        data-profesor="{{ $horario->Id_profesores }}" data-dia="{{ $horario->Dia }}"
                                        data-hora="{{ $horario->Hora }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="{{ route('horarios.destroy', $horario->Id_horarios) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar este horario?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger shadow-sm" title="Eliminar">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No se encontraron horarios.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4 mb-4">
    {{ $horarios->links('pagination::bootstrap-5') }}
</div>