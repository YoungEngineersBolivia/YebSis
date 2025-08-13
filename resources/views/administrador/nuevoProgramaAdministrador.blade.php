<div class="modal-header">
  <h5 class="modal-title">{{ $modo }} Programa</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>
<div class="modal-body">
  <form id="formPrograma" action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($modo === 'Editar')
      @method('PUT')
    @endif

    <input type="hidden" name="id" value="{{ $programa->id ?? '' }}">

    <!-- Campos -->
    <div class="row">
      <div class="col-md-6 mb-3">
        <label for="nombre" class="form-label">Nombre del Programa</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $programa->nombre ?? '' }}" {{ $readonly }}>
      </div>
      <div class="col-md-6 mb-3">
        <label for="costo" class="form-label">Costo (Bs)</label>
        <input type="number" class="form-control" id="costo" name="costo" step="0.01" value="{{ $programa->costo ?? '' }}" {{ $readonly }}>
      </div>
    </div>

    <!-- Otros campos... -->
    <div class="mb-3">
      <label for="descripcion" class="form-label">Descripción</label>
      <textarea class="form-control" id="descripcion" name="descripcion" rows="3" {{ $readonly }}>{{ $programa->descripcion ?? '' }}</textarea>
    </div>

    @if($modo !== 'Detalle')
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
    @endif
  </form>
</div>
